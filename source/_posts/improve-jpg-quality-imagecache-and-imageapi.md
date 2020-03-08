---
title: Improve JPG Quality in Imagecache and ImageAPI
date: 2010-06-02
excerpt: How to fix the quality of uploaded images in Drupal.
tags:
  - drupal-planet
  - drupal-6
  - imagecache
---
Whilst uploading images for my Projects and Testimonials sections, I noticed that the Imagecache-scaled images weren't as high a quality the originals on my Mac. I did some searching online and found out that, by default, Drupal resamples uploaded jpgs to 75% of their original quality.

To increase the quality of your images, change the setting in the two following places:

* admin/settings/imageapi/config
* admin/settings/image-toolkit

The first one is for ImageAPI. Primarily, this means Imagecache presets. The second one is for core's image.inc. This is used for resizing profile pictures in core, and some contrib modules. Once changed, I did have to flush each of the Imagecache presets (admin/dist/imagecache) for the changes to take effect.
