---
title: How to Use Environment Variables for your Drupal Settings with Docksal
date: 2018-06-04
excerpt: How to leverage environment variables with Drupal and Docksal.
tags:
    - drupal
    - drupal-planet
    - docksal
---
Within the [Docksal documentation for Drupal settings][0], the example database settings include hard-coded credentials to connect to the Drupal database. For example, within a `settings.php` file, you could add this:

```language-php
$databases['default']['default'] = [
  'driver' => 'mysql',
  'host' => 'db',
  'database' => 'myproject_db',
  'username' => 'myproject_user',
  'password' => 'myproject_pass',
];
```

Whilst this is fine, it does mean that there is duplication in the codebase as the database credentials can also be added as environment variations within `.docksal/docksal.env` - this is definitely the case if you want to use a custom database name, for example.

Also if one of these values were to change, then Drupal wouldn't be aware of that and would no longer be able to connect to the database.

It also means that the file can’t simply be re-used on another project as it contains project-specific credentials.

We can improve this by using the environment variables within the settings file.

The relevant environment variables are `MYSQL_DATABASE` for the database name, and `MYSQL_USER` and `MYSQL_PASSWORD` for the MySQL username and password. These can be set in `.docksal/docksal.env`, and will need to be present for this to work.

For example:

```
DOCKSAL_STACK=default
MYSQL_DATABASE=myproject_db
MYSQL_USER=myproject_user
MYSQL_PASSWORD=myproject_pass
```

With these in place, they can be referenced within the settings file using the `getenv()` function.

```
$databases['default']['default'] = [
  'driver' => 'mysql',
  'host' => 'db',
  'database' => getenv('MYSQL_DATABASE'),
  'username' => getenv('MYSQL_USER'),
  'password' => getenv('MYSQL_PASSWORD'),
];
```

Now the credentials are no longer duplicated, and the latest values from the environment variables will always be used.

However, you may see a message like this when you try and load the site:

> Drupal\Core\Database\DatabaseAccessDeniedException: SQLSTATE[HY000] [1045] Access denied for user ''@'172.19.0.4' (using password: NO) in /var/www/core/lib/Drupal/Core/Database/Driver/mysql/Connection.php on line 156

If you see this, the environment variables aren’t being passed into Docksal’s `cli` container, so the values are not being populated. To enable them, edit `.docksal/docksal.yml` and add `MYSQL_DATABASE`, `MYSQL_PASSWORD` and `MYSQL_USER` to the `environment` section of the `cli` service.

```language-yml
version: '2.1'
services:
  cli:
    environment:
      - MYSQL_DATABASE
      - MYSQL_PASSWORD
      - MYSQL_USER
```

After changing this file, run `fin start` to rebuild the project containers and try to load the site again.

[0]: https://docksal.readthedocs.io/en/master/advanced/drupal-settings
