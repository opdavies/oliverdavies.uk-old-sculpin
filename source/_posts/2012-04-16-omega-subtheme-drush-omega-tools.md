---
title: Create an Omega Subtheme with LESS CSS Preprocessor using Omega Tools and Drush
slug: create-omega-subtheme-less-css-preprocessor-using-omega-tools-and-drush
tags:
  - drupal-7
  - omega
  - theming
  - less
  - drupal-planet
  - drupal
use: [posts]
redirect:
    - blog/omega-subtheme-drush-omega-tools/
---
In this tutorial I'll be showing how to create an [Omega](http://drupal.org/project/omega) subtheme using the [Omega Tools](http://drupal.org/project/omega_tools) module, and have it working with the [LESS CSS preprocessor](http://lesscss.org).

The first thing that I need to do is download the Omega theme and the Omega Tools and [LESS](http://drupal.org/project/less "LESS module on drupal.org") modules, and then to enable both modules. I'm doing this using Drush, but you can of course do this via the admin interface at admin/modules.

    $ drush dl less omega omega_tools;
    $ drush en -y less omega_tools

With the Omega Tools module enabled I get the drush omega-subtheme command that creates my Omega subtheme programatically. Using this command, I'm creating a new subtheme, enabling it and setting it as the default theme on my site.

    $ drush omega-subtheme "Oliver Davies" --machine_name="oliverdavies" --enable --set-default

By default, four stylesheets are created within the subtheme's css directory. The first thing that I'm going to do is rename `global.css` to `global.less`.

    $ mv css/global.css css/global.less

Now I need to find all references to global.css within my oliverdavies.info file. I did this using `$ nano oliverdavies.info`, pressing `Ctrl+W` to search, then `Ctrl+R` to replace, entering `global.css` as the search phrase, and then `global.less` as the replacement text. After making any changes to oliverdavies.info, I need to clear Drupal's caches for the changes to be applied.

    $ drush cc all

I tested my changes by making some quick additions to my global.less file and reloading the page.

If your changes aren't applied, then confirm that your global.less file is enabled within your theme's configuration. I did this by going to admin/appearance/settings/oliverdavies, clicking on the Toggle styles tab within *Layout configuration* and finding global.less at the bottom of *Enable optional stylesheets*.
