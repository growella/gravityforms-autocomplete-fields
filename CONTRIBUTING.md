# Contributing to Gravity Forms: Autocomplete Fields

Thank you for your interest in contributing to Gravity Forms: Autocomplete Fields!


## Installation

To install the plugin for development, fork this repository into your GitHub account, then clone your fork into your local development environment's `wp-content/plugins/` directory, then run `composer install` inside the directory to pull in dependencies.

```bash
$ git clone https://github.com/{YOUR_USERNAME}/gravityforms-autocomplete-fields.git
$ cd gravityforms-autocomplete-fields
$ composer install
```


## Guidelines

In order to keep the plugin as stable as possible, we have a few guidelines for contributions.


### Coding standards

The plugin is written using the [WordPress Coding Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/), which are enforced via [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) with [the WordPress-Extra ruleset](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards).

New to PHP_CodeSniffer? No worries! Our `composer.json` file includes [WP Enforcer, a Composer package that automatically registers pre-commit git hooks when you install the project dependencies](https://github.com/stevegrunwell/wp-enforcer); simply commit as you normally would, WP Enforcer will let you know if there are any violations.


### Minimum PHP version

While the official minimum version of PHP supported is 5.3 (because the plugin uses namespaces), Travis-CI builds are only run against versions 5.6 and newer.


### Test coverage

The project strives to have as much unit test coverage as possible; the test suite uses [PHPUnit](https://phpunit.de/) and [WP_Mock](https://github.com/10up/wp_mock), the latter of which leverages [Mockery](http://docs.mockery.io/en/latest/).

[![Test Coverage](https://codeclimate.com/github/growella/gravityforms-autocomplete-fields/badges/coverage.svg)](https://codeclimate.com/github/growella/gravityforms-autocomplete-fields/coverage)


### Internationalization

Gravity Forms: Autocomplete Fields is built from the ground-up to be internationalized, enabling site owners all over the world to use the plugin. If you're contributing any textual content, [please be sure it's i18n-ready](https://codex.wordpress.org/I18n_for_WordPress_Developers).
