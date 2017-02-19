---
title: Improve JPG Quality in Imagecache and ImageAPI
slug: improve-jpg-quality-imagecache-and-imageapi
tags:
  - drupal-planet
  - drupal-6
  - imagecache
use: [posts]
redirect:
    - blog/improve-jpg-quality-imagecache-and-imageapi/
---
Whilst uploading images for my Projects and Testimonials sections, I noticed that the Imagecache-scaled images weren't as high a quality the originals on my Mac. I did some searching online and found out that, by default, Drupal resamples uploaded jpgs to 75% of their original quality.

To increase the quality of your images, change the setting in the two following places:

* admin/settings/imageapi/config
* admin/settings/image-toolkit

The first one is for ImageAPI. Primarily, this means Imagecache presets. The second one is for core's image.inc. This is used for resizing profile pictures in core, and some contrib modules. Once changed, I did have to flush each of the Imagecache presets (admin/build/imagecache) for the changes to take effect.
