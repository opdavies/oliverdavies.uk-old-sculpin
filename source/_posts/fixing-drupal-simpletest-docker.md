---
title: Fixing Drupal SimpleTest issues inside Docker Containers
date: 2017-05-05
excerpt: How I managed to get my Drupal SimpleTest tests to run and pass within Docker containers.
tags:
    - docker
    - drupal
    - drupal-planet
    - simpletest
    - testing
---
I’ve been a Drupal VM user for a long time, but lately I’ve been using a combination Drupal VM and Docker for my local development environment. There were a couple of issues preventing me from completely switching to Docker - one of which being that when I tried running of my Simpletest tests, a lot of them would fail where they would pass when run within Drupal VM.

Here’s an excerpt from my `docker-compose.yml` file:

**TL;DR** You need to include the name of your web server container as the `--url` option to `run-scripts.php`.

I’ve been a [Drupal VM][1] user for a long time, but lately I’ve been using a combination Drupal VM and [Docker][0] for my local development environment. There were a couple of issues preventing me from completely switching to Docker - one of which being that when I tried running of my Simpletest tests, a lot of them would fail where they would pass when run within Drupal VM.

Here’s an excerpt from my `docker-compose.yml` file:

```language-yaml
services:
  php:
    image: wodby/drupal-php:5.6
    volumes:
      - ./repo:/var/www/html

  nginx:
    image: wodby/drupal-nginx:7-1.10
    environment:
      NGINX_BACKEND_HOST: php
      NGINX_SERVER_ROOT: /var/www/html/web
    ports:
      - "80:80"
   volumes_from:
      - php
...
```

Nginx and PHP-FPM are running in separate containers, the volumes are shared across both and the Nginx backend is set to use the `php` container.

This is the command that I was using to run the tests:

```language-bash
$ docker-compose run --rm \
  -w /var/www/html/web \
  php \
  php scripts/run-tests.sh \
    --php /usr/local/bin/php \
    --class OverrideNodeOptionsTestCase
```

This creates a new instance of the `php` container, sets the working directory to my Drupal root and runs Drupal’s `run-tests.sh` script with some arguments. In this case, I'm running the `OverrideNodeOptionsTestCase` class for the override_node_options tests. Once complete, the container is deleted because of the `--rm` option.

This resulted in 60 of the 112 tests failing, whereas they all passed when run within a Drupal VM instance.

```language-markup
Test summary
------------

Override node options 62 passes, 60 fails, 29 exceptions, and 17 debug messages

Test run duration: 2 min 25 sec
```

Running the tests again with the`--verbose` option, I saw this message appear in the output below some of the failing tests:

> simplexml_import_dom(): Invalid Nodetype to import

**Up
After checking that I had all of the required PHP extensions installed, I ran `docker-compose exec php bash` to connect to the `php` container and ran `curl http://localhost` to check the output. Rather than seeing the HTML for the site, I got this error message:

> curl: (7) Failed to connect to localhost port 80: Connection refused

Whereas `curl http://nginx` returns the HTML for the page, so included it with the `--url` option to `run-tests.sh`, and this resulted in my tests all passing.

```language-bash
$ docker-compose run --rm \
  -w /var/www/html/web \
  php \
  php scripts/run-tests.sh \
    --php /usr/local/bin/php \
    --url http://nginx \
    --class OverrideNodeOptionsTestCase
```

```language-markup
Test summary
------------

Override node options 121 passes, 0 fails, 0 exceptions, and 34 debug messages

Test run duration: 2 min 31 sec
```

**Note:** In this example I have separate `nginx` and `php` containers, but I've tried and had the same issue when running Nginx and PHP-FPM in the same container - e.g. called `app` - and still needed to add `--url http://app` in order for the tests to run successfully.

I don’t know if this issue is macOS specfic (I know that [Drupal CI][2] is based on Docker, and I don’t know if it’s an issue) but I’m going to test also on my Ubuntu Desktop environment and investigate further and also compare the test run times for Docker in macOS, Docker in Ubuntu and within Drupal VM. I’m also going to test this with PHPUnit tests with Drupal 8.

[0]: https://www.docker.com
[1]: https://www.drupalvm.com
[2]: https://www.drupal.org/drupalorg/docs/drupal-ci
