---
title: Creating Local and Staging sites with Drupal's Domain Module Enabled
slug: creating-local-and-staging-sites-drupals-domain-module-enabled
tags:
  - drupal
  - drupal-planet
  - databases
  - domain
  - table-prefixing
use: [posts]
redirect:
    - blog/creating-local-and-staging-sites-drupals-domain-module-enabled/
---
{% block excerpt %}
The [Domain Access project](https://drupal.org/project/domain "The Domain Access project on Drupal.org") is a suite of modules that provide tools for running a group of affiliated sites from one Drupal installation and a single shared database. The issue is that the domains are stored within the database so these are copied across when the data is migrated between environments, whereas the domains are obviously going to change.

Rather than changing the domain settings within the Domain module itself, the best solution I think is to use table prefixes and create a different domain table per environment.
{% endblock %}

{% block content %}
The [Domain Access project](https://drupal.org/project/domain "The Domain Access project on Drupal.org") is a suite of modules that provide tools for running a group of affiliated sites from one Drupal installation and a single shared database. The issue is that the domains are stored within the database so these are copied across when the data is migrated between environments, whereas the domains are obviously going to change.

Rather than changing the domain settings within the Domain module itself, the best solution I think is to use table prefixes and create a different domain table per environment. With a live, staging and local domains, the tables would be named as follows:

~~~~
live_domain
local_domain
staging_domain
~~~~

Within each site's settings.php file, define the prefix for the domain table within the databases array so that each site is looking at the correct table for its environment.

~~~~php
$databases['default']['default'] = array(
  'driver' => 'mysql',
  'database' => 'foobar',
  'username' => 'foo',
  'password' => 'bar',
  'host' => 'localhost',
  'prefix' => array(
    'default' => '',
    'domain' => 'local_', // This will use the local_domain table.
    // Add any other prefixed tables here.
  ),
);
~~~~

Within each environment-specific domain table, update the subdomain column to contain the appropriate domain names.

**Update:** I've just found out about the Domain Alias sub-module, which you can enable and add aliases to each domain, so you could add the aliases in this way also.
{% endblock %}
