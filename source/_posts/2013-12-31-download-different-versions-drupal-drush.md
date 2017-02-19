---
title: Download Different Versions of Drupal with Drush
description: How to download different versions of Drupal core using Drush.
slug: download-different-versions-drupal-drush
tags:
  - drupal
  - drupal-planet
  - drush
use: [posts]
redirect:
    - blog/download-different-versions-drupal-drush/
---
{% block excerpt %}
If you use [Drush](https://raw.github.com/drush-ops/drush/master/README.md "About Drush"), it's likely that you've used the `drush pm-download` (or `drush dl` for short) command to start a new project. This command downloads projects from Drupal.org, but if you don't specify a project or type "drush dl drupal", the command will download the current stable version of Drupal core. Currently, this will be Drupal 7 with that being the current stable version of core at the time of writing this post.

But what if you don't want Drupal 7?
{% endblock %}

{% block content %}
I still maintain a number of Drupal 6 sites and occassionally need to download Drupal 6 core as opposed to Drupal 7. I'm also experimenting with Drupal 8 so I need to download that as well.

By declarding the core version of Drupal, such as "drupal-6", Drush will download that instead.

    $ drush dl drupal-6

This downloads the most recent stable version of Drupal 6. If you don't want that, you can add the --select and additionally the --all options to be presented with an entire list to chose from.

    $ drush dl drupal-6 --select
    $ drush dl drupal-6 --select --all

If you want the most recent development version, just type:

    $ drush dl drupal-6.x

The same can be done for other core versions of Drupal, from Drupal 5 upwards.

    # This will download Drupal 5
    $ drush dl drupal-5
    # This will download Drupal 8
    $ drush dl drupal-8

For a full list of the available options, type "drush help pm-download" into a Terminal window or take a look at the entry on [drush.ws](http://drush.ws/#pm-download, "The entry for pm-download on drush.ws").
{% endblock %}
