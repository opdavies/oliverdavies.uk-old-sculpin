---
title: Easily Embed TypeKit Fonts into your Drupal Website
slug: easily-embed-typekit-fonts-your-drupal-website
tags:
  - drupal-planet
  - drupal-6
  - typekit
use: [posts]
redirect:
    - blog/typekit-fonts/
---
To begin with, you will need to [register for a TypeKit account](https://typekit.com/plans) - there is a free version if you just want to try it out.

Next, you'll need to create a kit that contains the fonts that you want to use on your website. I've used [FF Tisa Web Pro](http://typekit.com/fonts/ff-tisa-web-pro).

Under 'Kit Settings', ensure that your website domain (e.g. mysite.com) is listed under 'Domains'.

Download and install the [@font-your-face](http://drupal.org/project/fontyourface) module onto your Drupal site, and to go admin/settings/fontyourface to configure it. Enter [your TypeKit API key](https://typekit.com/account/tokens), and click 'Import Typekit' to import your kits and fonts.

Go to admin/build/themes/fontyourface, and click 'Browse fonts to enable'. Click on the name of the font that you want to enable, check 'Enabled', and click 'Edit font' to save your changes.

With the font enabled, you can now use it in your CSS.
