---
title: Include environment-specific settings files on Pantheon
slug: pantheon-settings-files
tags:
  - drupal
  - drupal-planet
  - pantheon
  - settings.php
use: [posts]
redirect:
    - blog/pantheon-settings-files/
---
{% block excerpt %}
I was recently doing some work on a site hosted on [Pantheon](http://getpantheon.com) and came across an issue, for which part of the suggested fix was to ensure that the `$base_url` variable was explicitly defined within settings.php (this is also best practice on all Drupal sites).

The way that was recommended was by using a `switch()` function based on Pantheon's environment variable. For example:
{% endblock %}

{% block content %}
I was recently doing some work on a site hosted on [Pantheon](http://getpantheon.com) and came across an issue, for which part of the suggested fix was to ensure that the `$base_url` variable was explicitly defined within settings.php (this is also best practice on all Drupal sites).

The way that was recommended was by using a `switch()` function based on Pantheon's environment variable. For example:

~~~php
switch ($_SERVER['PANTHEON_ENVIRONMENT']) {
  case 'dev':
    // Development environment.
    $base_url = 'dev-my-site.gotpantheon.com';
    break;


  case 'test':
    // Testing environment.
    $base_url = 'test-my-site.gotpantheon.com';
    break;


  case 'live':
    // Production environment.
    $base_url = 'live-my-site.gotpantheon.com';
    break;
}
~~~

Whilst this works, it doesn't conform to the DRY (don't repeat yourself) principle and means that you also might get a rather long and complicated settings file, especially when you start using multiple switches and checking for the value of the environment multiple times.

My alternative solution to this is to include an environment-specific settings file.

To do this, add the following code to the bottom of settings.php:

~~~php
if (isset($_SERVER['PANTHEON_ENVIRONMENT'])) {
  if ($_SERVER['PANTHEON_ENVIRONMENT'] != 'live') {
    // You can still add things here, for example to apply to all sites apart
    // from production. Mail reroutes, caching settings etc.
  }

  // Include an environment-specific settings file, for example
  // settings.dev.php, if one exists.
  $environment_settings = __DIR__ . '/settings.' .  $_SERVER['PANTHEON_ENVIRONMENT'] . '.php';
  if (file_exists($environment_settings)) {
    include $environment_settings;
  }
}
~~~

This means that rather than having one long file, each environment has it's own dedicated settings file that contains it's own additional configuration. This is much easier to read and make changes to, and also means that less code is loaded and parsed by PHP. Settings that apply to all environments are still added to settings.php.

Below this, I also include a [similar piece of code](/blog/include-local-drupal-settings-file-environment-configuration-and-overrides/) to include a settings.local.php file. The settings.php file then gets committed into the [Git](http://git-scm.com) repository.

Within the sites/default directory, I also include an example file (example.settings.env.php) for reference. This is duplicated, renamed and populated accordingly.

~~~php
<?php

/**
 * This is a specific settings file, just for the x environment. Any settings
 * defined here will be included after those in settings.php.
 *
 * If you have also added a settings.local.php file, that will override any
 * settings stored here.
 *
 * No database credentials should be stored in this file as these are included
 * automatically by Pantheon.
 */

$base_url = '';
~~~

The environment specific files are also committed into Git and pushed to Pantheon, and are then included automatically on each environment.
{% endblock %}
