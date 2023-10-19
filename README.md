# ddev-core-dev

```
git clone git@git.drupal.org:project/drupal.git
cd drupal
ddev config --auto
ddev get justafish/ddev-drupal-core-dev
ddev start

# Included commands
ddev drupal list

# Install drupal
ddev drupal install

# Run PHPUnit tests
ddev exec ./vendor/bin/phpunit -c core core/modules/sdc/tests/ --verbose
```