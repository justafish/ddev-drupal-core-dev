[![add-on registry](https://img.shields.io/badge/DDEV-Add--on_Registry-blue)](https://addons.ddev.com)
[![tests](https://github.com/justafish/ddev-drupal-core-dev/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/justafish/ddev-drupal-core-dev/actions/workflows/tests.yml?query=branch%3Amain)
[![last commit](https://img.shields.io/github/last-commit/justafish/ddev-drupal-core-dev)](https://github.com/justafish/ddev-drupal-core-dev/commits)
[![release](https://img.shields.io/github/v/release/justafish/ddev-drupal-core-dev)](https://github.com/justafish/ddev-drupal-core-dev/releases/latest)

# DDEV Drupal Core Dev

This is a DDEV add-on for doing Drupal core development.

We're in #ddev-for-core-dev on [Drupal Slack](https://www.drupal.org/community/contributor-guide/reference-information/talk/tools/slack) (but please try and keep work
and feature requests in Issues where it's visible to all 🙏)

## Installation

```bash
git clone https://git.drupalcode.org/project/drupal.git drupal
cd drupal
ddev config --omit-containers=db --disable-settings-management
ddev composer install
ddev add-on get justafish/ddev-drupal-core-dev

# See included commands
ddev drupal list
```

The `drupal` command is an extension of core's [`drupal`](https://git.drupalcode.org/project/drupal/-/blob/11.x/core/scripts/drupal?ref_type=heads)
command. This allows you to perform some basic tasks without needing to install
`drush` which will alter your composer dependencies.

## Examples

```bash
# Install drupal
# Run "ddev drupal install" to see all available options
ddev drupal install standard

# Run PHPUnit tests
ddev phpunit core/modules/announcements_feed

# Run Nightwatch tests (currently only runs on Chrome)
ddev nightwatch --tag core
```

## Nightwatch Examples

You can watch Nightwatch running in real time at https://drupal.ddev.site:7900
for Chrome and https://drupal.ddev.site:7901 for Firefox. The password is
"secret". YMMV using Firefox as core tests don't currently run on it.

Only core tests

```bash
ddev nightwatch --tag core
```

Skip running core tests

```bash
ddev nightwatch --skiptags core
```

Run a single test

```bash
ddev nightwatch tests/Drupal/Nightwatch/Tests/exampleTest.js
```

a11y tests for both the admin and default themes

```bash
ddev nightwatch --tag a11y
```

a11y tests for the admin theme only

```bash
ddev nightwatch --tag a11y:admin
```

a11y tests for the default theme only

```bash
ddev nightwatch --tag a11y:default
```

a11y test for a custom theme used as the default theme

```bash
ddev nightwatch --tag a11y:default --defaultTheme bartik
```

a11y test for a custom admin theme

```bash
ddev nightwatch --tag a11y:admin --adminTheme seven
```

## Core Linting

This will run static tests against core standards.

```bash
ddev drupal lint:phpstan
ddev drupal lint:phpcs
ddev drupal lint:js
ddev drupal lint:css
ddev drupal lint:cspell
# CSpell against only modified files
ddev drupal lint:cspell --modified-only
```

You can run all linting with `ddev drupal lint`, or with fail-fast turned on:
`ddev drupal lint --stop-on-failure`

## Credits

**Contributed and maintained by [@justafish](https://github.com/justafish)**
