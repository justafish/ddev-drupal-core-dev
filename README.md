# ddev-core-dev

This is a DDEV addon for doing Drupal core development.

```
git clone git@git.drupal.org:project/drupal.git
cd drupal
ddev config --auto
ddev get justafish/ddev-drupal-core-dev
ddev start
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