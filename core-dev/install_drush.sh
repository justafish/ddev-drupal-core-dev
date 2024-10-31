#!/bin/bash

#ddev-generated
# This script installs drush/drush without changing the git
# status of the Drupal checkout

set -eu -o pipefail

if command -v drush >/dev/null; then
  echo "drush is already installed, taking no action. You can remove it if you want to reinstall"
  exit
fi

echo "Installing drush without affecting git status"

# Make certain that we have something staged so we can create stash
touch .makedrush.txt
git add .makedrush.txt

# Save the stash, which will include anything people were doing
# plus the .makedrush.txt. This gets us back to "no changes"
# in `git status`
git stash

# Install drush
composer require drush/drush --with-dependencies

# Roll back to what we started with. Cleans up
# composer.* and anything else that the drush install changes
# But vendor directory is untouched since
# it's gitignored
git reset --hard

# Restore anything that might have been staged
# prior to the start
git stash pop

# Get rid of our dummy file
git rm -f .makedrush.txt

echo "drush/drush is installed in $(which drush)"
