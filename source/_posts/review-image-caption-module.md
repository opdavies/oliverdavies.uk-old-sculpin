---
title: Review of the Image Caption Module
date: 2010-08-20
excerpt: My review of Drupal’s Image Caption module.
tags:
  - drupal-planet
  - drupal
  - drupal-6
  - imagefield
  - image-caption
---
Up until as recent as last week, whenever I added an image into one of my Blog posts, I was manually adding the caption below each image and styling it accordingly. That was until I installed the [Image Caption](http://drupal.org/project/image_caption) module.

The Image Caption module uses jQuery to dynamically add captions to images. Here is a walkthrough of the process that I followed to install and configure the module. As always, I used Drush to download and enable the module, then visited the Image Caption Settings page (admin/settings/image_caption). Here, I select which node types should be included in image captioning. In my case, I only wanted this to apply to Blog posts.

As I use the [FileField](http://drupal.org/project/filefield), [ImageField](http://drupal.org/project/imagefield) and [Insert](http://drupal.org/project/insert) modules to add images to my posts, as opposed to via a WYSIWYG editor, I'm able to add the CSS class of 'caption' to my images.

Now, all images inserted this way will have the CSS class of 'caption'.

As the Image Caption module uses the image's title tag to create the displayed caption, I enabled the custom title text for my Image field so that when I upload an image, I'm prompted to enter text for the caption.

This results in a span called `image-caption-container` around the inserted image, and a caption below it called `image-caption` containing the text.

All that's left is to style these classes within your CSS stylesheet.
