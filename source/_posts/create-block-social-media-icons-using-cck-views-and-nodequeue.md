---
title: Create a Block of Social Media Icons using CCK, Views and Nodequeue
date: 2010-06-23
excerpt: How to create a block of social media icons in Drupal.
tags:
  - drupal-planet
  - drupal-6
  - drupal
  - views
  - nodequeue
  - oliverdavies.co.uk
---
I recently decided that I wanted to have a block displayed in a sidebar on my site containing icons and links to my social media profiles - [Twitter](http://twitter.com/opdavies), [Facebook](http://facebook.com/opdavies) etc. I tried the [Follow](http://drupal.org/project/follow) module, but it lacked the option to add extra networks such my [Drupal.org](http://drupal.org/user/381388) account, and my [RSS feed](http://oliverdavies.co.uk/rss.xml). I started to create my own version, and then found [this Blog post](http://www.hankpalan.com/blog/drupal-themes/add-your-social-connections-drupal-icons) by Hank Palan.

I created a 'Social icon' content type with the body field removed, and with fields for a link and image - then downloaded the favicons from the appropriate websites to use.

However, instead of using a custom template (node-custom.tpl.php) file, I used the Views module.

I added fields for the node titles, and the link from the node's content. Both of these are excluded from being displayed on the site. I then re-wrote the output of the Icon field to create the link using the URL, and using the node's title as the image's alternative text and the link's title.

I also used the [Nodequeue](http://drupal.org/project/nodequeue) module to create a nodequeue and arrange the icons in the order that I wanted them to be displayed. Once this was added as a relationship within my View, I was able to use node's position in the nodequeue as the sort criteria.

To complete the process, I used the [CSS Injector](http://drupal.org/project/css_injector) module to add some additional CSS styling to position and space out the icons.
