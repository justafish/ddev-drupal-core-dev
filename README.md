# ddev-core-dev

This is a DDEV addon for doing Drupal core development.

We're in #ddev-for-core-dev on [Drupal Slack](https://www.drupal.org/community/contributor-guide/reference-information/talk/tools/slack) (but please try and keep work and feature requests in Issues where it's visible to all 🙏)

```
git clone https://git.drupalcode.org/project/drupal.git drupal
cd drupal
ddev config --omit-containers=db --disable-settings-management
ddev start
ddev add-on get justafish/ddev-drupal-core-dev 
ddev restart
ddev composer install

# See included commands
ddev drupal list

# Install drupal
ddev drupal install

# Run PHPUnit tests
ddev phpunit core/modules/sdc

# Run Nightwatch tests (currently only runs on Chrome)
ddev nightwatch --tag core
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
