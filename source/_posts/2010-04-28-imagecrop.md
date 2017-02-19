---
title: Using ImageCache and ImageCrop for my Portfolio
slug: using-imagecache-and-imagecrop-my-portfolio
tags:
  - drupal
  - drupal-6
  - cck
  - imagecache
  - imagecrop
  - imagefield
use: [posts]
redirect:
    - blog/using-imagecache-and-imagecrop-my-portfolio/
---
Whilst working on my own portfolio/testimonial website, I decided to have a portfolio page displaying the name of each site and a thumbnail image. For this Blog post, I'll be using a site called [Popcorn Strips](http://popcornstrips.com) which I built for a friend earlier this year as an example.

I created a content type called 'Project' with a CCK ImageField called 'Screenshot'. I created a project called [Popcorn Strips](http://popcornstrips.com), used the [ScreenGrab](https://addons.mozilla.org/addon/1146) add-on for Mozilla Firefox to take a screenshot of the website, and uploaded it to the project node.

I created a View to display the published projects, and an ImageCache 
preset to create the thumbnail image by scaling and cropping the image 
to a size of 200x100 pixels. 

Although, this automatically focused the crop on the centre of the image, whereas I wanted to crop from the top and left of the image - showing the site's logo and header. 

I installed the [ImageCrop](http://drupal.org/project/imagecrop) module, which add a jQuery crop function to the standard ImageCache presents. I removed the original Scale and Crop action and replaced it with a Scale action with a width of 200px. 

I then added a new 'Javascript crop' action with the following settings:

* Width: 200px
* Height: 100px
* xoffset: Left
* yoffset: Top
