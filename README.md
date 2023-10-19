# ddev-core-dev

This is a DDEV addon for doing Drupal core development.

```
git clone git@git.drupal.org:project/drupal.git
cd drupal
ddev config --auto
ddev get justafish/ddev-drupal-core-dev
ddev start

# See included commands
ddev drupal list

# Install drupal
ddev drupal install

# Run PHPUnit tests
ddev phpunit core/modules/sdc
```