---
title: How to run Drupal 8 PHPUnit Tests within Docksal from PhpStorm
date: 2018-07-19
excerpt: How to configure PhpStorm to run automated tests within Docksal.
tags:
    - docksal
    - drupal
    - drupal-8
    - phpstorm
    - phpunit
    - testing
promoted: true
---
I’ve recently re-watched [A Clean PHPUnit Workflow in PHPStorm][0] on [Laracasts][1], where Jeffrey configures PhpStorm to run tests from within the IDE. With Drupal 8 using PHPUnit too, I decided to try and do the same with a local D8 site.

Though because I’m using [Docksal][4] for my local development environment which, at least on a Mac, runs Docker containers within a virtual machine, there were some additional steps needed to achieve this and to have the tests run within the Docksal virtual machine and using the correct containers.

In this post, I’ll be using my [Drupal Testing Workshop codebase][2] as an example, which is based on the [Drupal Composer project][3] with some pre-configured Docksal configuration.

This post is separated into a few different sections:

- [Allow PhpStorm to connect to the CLI container](#allow-phpstorm-to-connect-to-the-cli-container)
- [Add a new deployment server](#add-a-new-deployment-server)
- [Configure PHP interpreter](#configuring-the-php-interpreter)
- [Set up PHPUnit in PhpStorm](#set-up-phpunit-in-phpstorm)
- [Running tests](#running-tests)

## Allow PhpStorm to connect to the CLI container

The first thing to do is to allow PhpStorm to connect to Docksal’s CLI container to allow it to run the tests. We can do this by exposing the container’s SSH port so that it’s available to the host machine and PhpStorm.

As this is going to be unique to my environment, I’m going to add this to `.docksal/docksal-local.yml` which I have in `.gitignore`, rather than committing it into the repository and enforcing the same port number for everyone else and potentially causing conflicts.

In this case I’ll expose port 22 in the container to port 2225 locally.

```
version: '2.1'

services:
  cli:
    ports:
      - '2225:22'
```

Once added, run `fin start` to rebuild the project’s containers.

You can verify the change by running `fin ps` and you should see something like `0.0.0.0:2225->22/tcp` under Ports for the CLI container.

## Add a new Deployment server

Now PhpStorm can connect to Docksal, I can configure it to do so by adding a new deployment server.

- Open PhpStorm’s preferences, and go to 'Build, Execution, Deployment' and 'Deployment'.
- Click 'Add' to configure a new deployment server.
- Enter a name like 'Docksal', and select SFTP as the server type.

![Adding a new deployment server](/images/blog/phpstorm-phpunit-docksal/deployment-1.png){.with-border .sm:max-w-sm}

### Connection settings

On the Connection tab:

- Enter your domain name - e.g. `drupaltest.docksal` as the SFTP host. This will resolve to the correct local IP address.
- Enter the exposed port for the CLI container that was entered in the previous step.
- Enter "docker" as both the username and password.

You should now be able to click "Test SFTP connection" and get a successfully connected confirmation message.

![Configuring a new deployment server](/images/blog/phpstorm-phpunit-docksal/deployment-2.png)

### Mapping settings

On the Mappings tab, add `/var/www` as the deployment path so that PhpStorm is looking in the correct place for the project code.

![Add mappings to the deployment server](/images/blog/phpstorm-phpunit-docksal/deployment-3.png){.with-border}

## Configuring the PHP Interpreter

In Preferences, search for 'PHP' within 'Languages & Frameworks', and add a new CLI interpreter.

![The PHP preferences in PhpStorm](/images/blog/phpstorm-phpunit-docksal/cli-interpreter-1.png){.with-border}

In this case I’ve called it 'Docksal PHP 7.1', used the Docksal deployment configuration, and set the path to the PHP executable to `/usr/local/bin/php` (the same path that we would get if we ran `fin run which php`). You should see both the deployment host URL displayed as well as the remote PHP version and configuration filenames.

![Configuring a new CLI interpreter](/images/blog/phpstorm-phpunit-docksal/cli-interpreter-2.png){.with-border}

This can now be selected as the CLI interpreter for this project.

![Selecting the new CLI interpreter in the PHP preferences](/images/blog/phpstorm-phpunit-docksal/cli-interpreter-3.png){.with-border}

## Set up PHPUnit in PhpStorm

In Preferences, search for 'Test Frameworks' and add a new framework.

![Adding a new test framework (PHPUnit) in PHPStorm](/images/blog/phpstorm-phpunit-docksal/phpunit-1.png){.with-border}

Select 'PHPUnit by Remote Interpreter' and then the 'Docksal PHP 7.1' that we created in the last step.

Select 'Use Composer autoloader' for the PHPUnit library setting so that PhpStorm uses the version required by Drupal core, and set the path to `/var/www/vendor/autoload.php`.

Also specify the path to the default (phpunit.xml) configuration file. This will depend on how your project is structured, in this case it’s at `/var/www/web/core/phpunit.xml`.

![Configuring PHPUnit in PHPstorm](/images/blog/phpstorm-phpunit-docksal/phpunit-4.png){.with-border}

## Running tests

With PHPUnit configured, next to each test class and method, you can see a green circle (or a red one if the test failed the last run). You can click the circle and select to run that test class or method. You can also right-click directories in the project sidebar to run all of the tests within that directory.

![Running a test within PhpStorm](/images/blog/phpstorm-phpunit-docksal/running-tests-1.png){.with-border}

When the tests start running, a new tool window will open that shows you all of the selected tests, how long each test took to run and whether it passed or failed. You can also see the CLI output from PHPUnit itself next to it.

![The tests results being displayed](/images/blog/phpstorm-phpunit-docksal/running-tests-2.png){.with-border}

From here, you also have the ability to re-run all of the tests, as well as a single test method or a specific test class.

Any test failures are shown here too, and for some failures like differences between two arrays you can use PhpStorm’s internal comparison tools to view the difference rather than needing to do so on the command line.

![Showing a failing test](/images/blog/phpstorm-phpunit-docksal/test-failure-1.png){.with-border}

![Displaying the difference between two arrays](/images/blog/phpstorm-phpunit-docksal/test-failure-2.png){.with-border .sm:max-w-md}

### Keyboard shortcuts

As per the video, I’ve also added some keyboard shortcuts to my keymap, so I can press ⌘T to run the current test method or class that I’m in, and ⇧⌘T to re-run the last test.

![Adding a keyboard shortcut to run the current test](/images/blog/phpstorm-phpunit-docksal/keyboard-shortcuts-1.png){.with-border}

![Adding a keyboard shortcut to re-run the last test](/images/blog/phpstorm-phpunit-docksal/keyboard-shortcuts-2.png){.with-border}

### Database issues

When running functional tests that require a database, I was getting a database error like the one below:

> Drupal\Core\Installer\Exception\InstallerException : Resolve all issues below to continue the installation. For help configuring your database server, see the <a href="https://www.drupal.org/getting-started/install">installation handbook</a>, or contact your hosting provider.

In `settings.php`, I check for the presence of `/.dockerenv` to ensure that we’re inside a Docker container, as well as the presence of a `docksal.settings.yml` file. The latter contains the database credentials for Drupal to connect to the MySQL database.

```php
if (file_exists('/.dockerenv') && file_exists(__DIR__ . '/docksal.settings.php')) {
  include __DIR__ . '/docksal.settings.php';
}
```

In order to get the tests to run, I had to prevent this file from being loaded during the tests. I can do this by checking that `SIMPLETEST_DB`, an environment variable set in phpunit.xml is not present.

```php
// settings.php

if (file_exists('/.dockerenv') && file_exists(__DIR__ . '/docksal.settings.php') && !getenv('SIMPLETEST_DB')) {
  include __DIR__ . '/docksal.settings.php';
}
```

With this extra condition, the database credentials are loaded correctly and the functional tests run properly.

Happy testing!

[0]: https://laracasts.com/series/php-bits/episodes/2
[1]: https://laracasts.com
[2]: https://github.com/opdavies/drupal-testing-workshop
[3]: https://github.com/drupal-composer/drupal-project
[4]: https://docksal.io
