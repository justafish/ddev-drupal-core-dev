# ddev-core-dev

This is a DDEV addon for doing Drupal core development.

We're in #ddev-for-core-dev on [Drupal Slack](https://www.drupal.org/community/contributor-guide/reference-information/talk/tools/slack) (but please try and keep work and feature requests in Issues where it's visible to all 🙏)

`ddev drush` is fully supported, along with using or testing MariaDB, MySQL, and PostgreSQL databases (and Sqlite3)


```
git clone https://git.drupalcode.org/project/drupal.git drupal
cd drupal
ddev config --project-type=drupal
ddev get justafish/ddev-drupal-core-dev
ddev restart
ddev composer install
ddev config --update


# Install drupal
ddev drush si -y --account-pass==admin

# Run PHPUnit tests
ddev phpunit core/modules/sdc

# Run Nightwatch tests (currently only runs on Chrome)
ddev nightwatch --tag core
```

## Using various database types

By default, the DDEV default database type is used (MariaDB). 

To use another supported database type, 
`ddev delete -Oy` and `ddev config --database=mysql:8.0` or `ddev config --database=postgres:16` for example.

To use Sqlite, 
```
ddev stop
ddev config --disable-settings-management --omit-containers=db
rm -rf web/sites/default/settings*.php web/sites/default/files
ddev start
ddev drupal install
```

## Nightwatch Examples

You can watch Nightwatch running in real time at https://drupal.ddev.site:7900
for Chrome and https://drupal.ddev.site:7901 for Firefox. The password is
"secret". YMMV using Firefox as core tests don't currently run on it.

Only core tests
```
ddev nightwatch --tag core
```

Skip running core tests
```
ddev nightwatch --skiptags core
```

Run a single test
```
ddev nightwatch tests/Drupal/Nightwatch/Tests/exampleTest.js
```

a11y tests for both the admin and default themes
```
ddev nightwatch --tag a11y
```

a11y tests for the admin theme only
```
ddev nightwatch --tag a11y:admin
```

a11y tests for the default theme only
```
ddev nightwatch --tag a11y:default
```

a11y test for a custom theme used as the default theme
```
ddev nightwatch --tag a11y:default --defaultTheme bartik
```

a11y test for a custom admin theme
```
ddev nightwatch --tag a11y:admin --adminTheme seven
```

## Core Linting

This will run static tests against core standards.

```
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