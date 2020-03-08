---
title: Create a Slideshow of Multiple Images Using Fancy Slide
date: 2010-05-25
excerpt: How to create a slideshow of images using Drupal’s Fancy Slide module.
tags:
  - drupal-planet
  - drupal
  - drupal-6
  - fancy-slide
  - slideshow
---
Whilst updating my About page, I thought about creating a slideshow of several images instead of just the one static image. When I looking on Drupal.org, the only slideshow modules were to create slideshows of images that were attached to different nodes - not multiple images attached to one node. Then, I found the [Fancy Slide](http://drupal.org/project/fancy_slide) module. It's a jQuery Slideshow module with features that include integration with the [CCK](http://drupal.org/project/cck), [ImageCache](http://drupal.org/project/imagecache) and [Nodequeue](http://drupal.org/project/nodequeue) modules.

I added an CCK Image field to my Page content type, and set the number of values to 3, then uploaded my images to the Page.

Whilst updating my About page, I thought about creating a slideshow of several images instead of just the one static image. When I looking on Drupal.org, the only slideshow modules were to create slideshows of images that were attached to different nodes - not multiple images attached to one node. Then, I found the [Fancy Slide](http://drupal.org/project/fancy_slide) module. It's a jQuery Slideshow module with features that include integration with the [CCK](http://drupal.org/project/cck), [ImageCache](http://drupal.org/project/imagecache) and [Nodequeue](http://drupal.org/project/nodequeue) modules.
Once the Images were added, I went to the Fancy Slide settings page and created the slideshow.

I added the dimensions of my images, the type of animation, specified the node that contained the images, the slideshow field, delay between slides and transition speed. With the slideshow created, it now needed embedding into the page.

I added the following code into my About page, as described in the Fancy Slide readme.txt file - the number representing the ID of the slideshow.

```language-php
<?php print theme('fancy_slide', 1); ?>
```

In my opinion, this adds a nice effect to the About page. I like it because it's easy to set up, and easy to add additional images later on if required.
