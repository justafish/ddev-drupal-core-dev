# ddev-drupal-core-dev

* [What is ddev-drupal-core-dev?](#what-is-ddev-drupal-core-dev)
* [Initial Setup](#initial-setup)
* [Install Drupal](#install-drupal)
* [PHPUnit](#phpunit)
* [Nighwatch](#nightwatch)

## What is ddev-drupal-core-dev?
This is a [DDEV](https://github.com/ddev/ddev) add-on for doing Drupal Core development.


## Initial Setup

We're in #ddev-for-core-dev on [Drupal Slack](https://www.drupal.org/community/contributor-guide/reference-information/talk/tools/slack) (but please try and keep work and feature requests in Issues where it's visible to all 🙏)

```
git clone https://git.drupalcode.org/project/drupal.git drupal
cd drupal
ddev config --omit-containers=db --disable-settings-management
ddev start
ddev corepack enable
ddev get justafish/ddev-drupal-core-dev
ddev restart
ddev composer install
````
Because `ddev-drupal-core-dev` is using SQLite the database container could be omitted on `ddev config --project-type=drupal10 --omit-containers=db`.


## Install Drupal

To get an overview of the list of available tasks:

```
ddev drupal list
````

Next install a demo site, which is not intended for production:

````
ddev drupal install
````
> :warning: To avoid unstaged conflicts in git, **do not** `ddev composer require drush/drush`.  A subset of that kind of functionality is available with `ddev drupal list`.

In case you need to drop your database tables because you are for example working in the context of the Drupal installer you can simply manually delete the SQLite database file located in `sites/default/files/.sqlite`.


## PHPUnit

```
ddev phpunit core/modules/sdc
```


## Nightwatch

You can watch Nightwatch running in real time at https://drupal.ddev.site:7900
for Chrome and https://drupal.ddev.site:7901 for Firefox. The password is
"secret". YMMV using Firefox as core tests don't currently run on it.(currently only runs on Chrome)

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