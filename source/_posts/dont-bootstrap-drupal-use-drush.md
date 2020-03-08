---
title: Don't Bootstrap Drupal, Use Drush
date: 2013-11-19
excerpt: Avoid bootstrapping Drupal manually in your scratch files - Drush has you covered!
tags:
  - drush
  - drupal-planet
  - php
---
There are times when doing Drupal development when you need to run a custom PHP script, maybe moving data from one field to another, that doesn't warrant the time and effort to create a custom module. In this scenario, it would be quicker to write a .php script and bootstrap Drupal to gain access to functions like `node_load()` and `db_query()`.

To bootstrap Drupal, you would need to add some additional lines of code to the stop of your script. Here is an alternative way.

```language-php
<?php

// Bootstrap Drupal.
$drupal_path = $_SERVER['DOCUMENT_ROOT'];
define('DRUPAL_ROOT', $drupal_path);
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

// Do stuff.
$node = node_load(1);
```

The script would need be placed in the root of your Drupal directory, and you would then have had to open a browser window and visit http://example.com/foo.php to execute it. This is where the "drush php-script" command (or "drush scr" for short) is useful, and can be used to execute the script from the command line.

```language-bash
$ drush scr foo.php
```

It also means that I no longer need to manually bootstrap Drupal, so my script is much cleaner.

```language-php
// Just do stuff.
$node = node_load(1);
```

I prefer to keep these scripts outside of my Drupal directory in a separate "scripts" directory (with Drupal in a "drupal" directory on the same level). This makes it easier to update Drupal as I don't need to worry about accidentally deleting the additional files. From within the drupal directory, I can now run the following command to go up one level, into the scripts directory and then execute the script. Note that you do not need to include the file extension.

```language-bash
$ drush scr ../scripts/foo
```

Or, if you're using [Drush aliases](http://deeson-online.co.uk/labs/drupal-drush-aliases-and-how-use-them "Drupal, Drush aliases, and how to use them"):

```language-bash
$ drush @mysite.local scr foo
```

If you commonly use the same scripts for different projects, you could also store these within a separate Git repository and checkout the scripts directory using a [Git submodule](http://git-scm.com/book/en/Git-Tools-Submodules "Git Submodules").
