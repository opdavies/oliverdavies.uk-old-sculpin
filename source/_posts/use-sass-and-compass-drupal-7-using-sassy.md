---
title: How to use SASS and Compass in Drupal 7 using Sassy
date: 2012-12-06
excerpt: Use PHPSass and the Sassy module to use Sass and Compass in your Drupal theme.
tags:
  - compass
  - css
  - drupal
  - drupal-7
  - drupal-planet
  - less
  - preprocessing
  - sass
---
I've recently started using [SASS](http://sass-lang.com) rather than LESS to do my CSS preprocessing - namely due to its integration with [Compass](http://compass-style.org) and it's built-in CSS3 mixins. Here are three modules that provide the ability to use SASS within Drupal.

* [Sassy](http://drupal.org/project/sassy "Sassy module on drupal.org")
* [Prepro](http://drupal.org/project/prepro "Prepro module on drupal.org")
* [Libraries API](http://drupal.org/project/libraries "Libraries API module on drupal.org")

Alternatively, you could use a base theme like [Sasson](http://drupal.org/project/sasson "Sasson theme on drupal.org") that includes a SASS compiler.

## Download the PHPSass Library

The first thing to do is download the PHPSass library from [GitHub](https://github.com/richthegeek/phpsass "PHPSass on GitHub"), as this is a requirement of the Sassy module and we can't enable it without the library. So, in a Terminal window:

```language-bash
$ mkdir -p sites/all/libraries; 
$ cd sites/all/libraries; 
$ wget https://github.com/richthegeek/phpsass/archive/master.tar.gz; 
$ tar zxf master.tar.gz; 
$ rm master.tar.gz; 
$ mv phpsass-master/ phpsass
```

Or, if you're using Drush Make files:

```language-ini
libraries[phpsass][download][type] = "get"
libraries[phpsass][download][url] = "https://github.com/richthegeek/phpsass/archive/master.tar.gz"
```

The PHPSass library should now be located at `sites/all/libraries/phpsass`.

## Download and enable the Drupal modules

This is easy if you use [Drush](http://drupal.org/project/drush):

```language-bash
$ drush dl libraries prepro sassy
$ drush en -y libraries prepro sassy sassy_compass
```

Otherwise, download the each module from it's respective project page and place it within your `sites/all/modules` or `sites/all/modules/contrib` directory.

## Configuring the Prepro module

The Prepro module provides various settings that can be changed for each preprocessor. Go to `admin/config/media/prepro` to configure the module as required.

Personally, in development, I'd set caching to 'uncached' and the error reporting method to 'show on page'. In production, I'd change these to "cached" and "watchdog" respectively. I'd also set the output style to "compressed",

## Adding SASS files into your theme

With this done, you can now add SASS and SCSS files by adding a line like `stylesheets[all][] = css/base.scss` in your theme's .info file.
