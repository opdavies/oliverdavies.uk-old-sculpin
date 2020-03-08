---
title: Create a Zen Sub-theme Using Drush
date: 2013-09-06
excerpt: How to quickly create a Zen sub-theme using Drush.
tags:
  - drupal
  - drupal-planet
  - drush
  - zen
  - theming
---
How to use [Drush](https://drupal.org/project/drush) to quickly build a new sub-theme of [Zen](https://drupal.org/project/zen).

First, download the [Zen](https://drupal.org/project/zen "The Zen theme") theme if you haven't already done so.

```language-bash
$ drush dl zen
```

This will now enable you to use the "drush zen" command.

```language-bash
$ drush zen "Oliver Davies" oliverdavies --description="A Zen sub-theme for oliverdavies.co.uk" --without-rtl
```

The parameters that I'm passing it are:

1. The human-readable name of the theme.
2. The machine-readable name of the theme.
3. The description of the theme (optional).
4. A flag telling Drush not to include any right-to-left elements within my sub-theme as these aren't needed (optional).

This will create a new theme in sites/all/themes/oliverdavies.

For further help, type `$ drush help zen` to see the Drush help page for the zen command.
