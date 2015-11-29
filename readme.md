# Translation Module

[![Latest Version](https://img.shields.io/packagist/v/asgardcms/translation-module.svg?style=flat-square)](https://github.com/asgardcms/translation/releases)

[![Total Downloads](https://img.shields.io/packagist/dd/asgardcms/translation-module.svg?style=flat-square)](https://packagist.org/packages/asgardcms/translation-module)
[![Total Downloads](https://img.shields.io/packagist/dm/asgardcms/translation-module.svg?style=flat-square)](https://packagist.org/packages/asgardcms/translation-module)
[![Total Downloads](https://img.shields.io/packagist/dt/asgardcms/translation-module.svg?style=flat-square)](https://packagist.org/packages/asgardcms/translation-module)
[![Slack](http://slack.asgardcms.com/badge.svg)](http://slack.asgardcms.com/)

Easily manage your translations via the backend GUI.

Contains all the translations files for the AsgardCms Modules. 

## Installation

#### Require the module in your project
```
composer require asgardcms/translation-module
```

#### Publish the configuration

```
php artisan module:publish translation
```

The configuration has one option: `translations-gui` which you can set to a boolean value. Setting this to true will have a *slight* performance hit, but it will give you (and your client) the possibility to edit static translations via a GUI. 

If you don't have that need, set this to false.


#### Permissions

Don't forget to give yourself the permissions to the translation module if you have enabled the `translations-gui` option.


#### Missing assets ? Publish the assets.

If you haven't altered the post-update scripts in the composer file, it will publish the translation module assets to the public folder.

However if you did alter it, or you're getting an `AssetNotFound` exception, you can publish those assets manually using the following command:

```
php artisan module:publish translation
```