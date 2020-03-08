---
title: Include a Local Drupal Settings file for Environment Configuration and Overrides
date: 2014-12-20
excerpt: How to create and include a local settings file to define and override environment-specific variables.
tags:
  - drupal
  - drupal-6
  - drupal-7
  - drupal-8
  - drupal-planet
  - settings.php
---
How to create and include a local settings file to define and override environment-specific variables, and keep sensitive things like your database credentials and API keys safe.

At the bottom of settings.php, add the following code:

```language-php
$local_settings = __DIR__ . '/settings.local.php';
if (file_exists($local_settings)) {
  include $local_settings;
}
```

This allows for you to create a new file called settings.local.php within a sites/* directory (the same place as settings.php), and this will be included as an extension of settings.php. You can see the same technique being used within Drupal 8's [default.settings.php](http://cgit.drupalcode.org/drupal/tree/sites/default/default.settings.php#n621) file.

Environment specific settings like `$databases` and `$base_url` can be placed within the local settings file. Other settings like `$conf['locale_custom_strings_en']` (string overrides) and `$conf['allow_authorize_operations']` that would apply to all environments can still be placed in settings.php. 

settings.php though is ignored by default by Git by a .gitignore file, so it won't show up as a file available to be committed. There are two ways to fix this. The first is to use the `--force` option when adding the file which overrides the ignore file:

```language-bash
git add --force sites/default/settings.php
```

The other option is to update the .gitignore file itself so that settings.php is no longer ignored. An updated .gitignore file could look like:

```language-bash
# Ignore configuration files that may contain sensitive information.
sites/*/settings.local*.php

# Ignore paths that contain user-generated content.
sites/*/files
sites/*/private
```

This will allow for settings.php to be added to Git and committed, but not settings.local.php.
