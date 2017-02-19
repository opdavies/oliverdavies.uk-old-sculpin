---
title: Create a Flickr Photo Gallery Using Feeds, CCK and Views
slug: create-flickr-photo-gallery-using-feeds-cck-and-views
tags:
  - drupal-planet
  - drupal-6
  - photo-gallery
  - views
  - cck
  - imagecache
  - feeds
  - filefield
  - flickr
  - imagefield
use: [posts]
redirect:
    - blog/create-flickr-photo-gallery-using-feeds-cck-and-views/
---
In this tutorial, I'll show you how to create a photo gallery which uses photos imported from [Flickr](http://www.flickr.com).

The modules that I'll use to create the Gallery are:

* [CCK](http://drupal.org/project/cck)
* [Feeds](http://drupal.org/project/feeds)
* [Feeds Image Grabber](http://drupal.org/project/feeds_imagegrabber)
* [FileField](http://drupal.org/project/filefield)
* [ImageAPI](http://drupal.org/project/imageapi)
* [ImageCache](http://drupal.org/project/imagecache)
* [ImageField](http://drupal.org/project/imagefield)
* [Views](http://drupal.org/project/views)

The first thing that I did was to create a content type to store my imported images. I named it 'Photo', removed the Body field, and added an Image field.

Next, I installed and configured the Feeds and Image Grabber module. I used an overridden default Feed to import my photos from Flickr using the following settings:

* **Basic settings:** I changed the Refresh time to 15 minutes.
* **Processor settings:** I changed the content type to 'Photo', and the author's name from 'anonymous'.
* **Processor mapping:** I added a new mapping from 'Item URL (link)' to 'Photo (FIG)'. The Photo FIG target is added by the Image Grabber module.

Next, I needed to create the actual Feed, which I did by clicking 'Import' within the Navigation menu,  and clicking 'Feed'. I gave it a title, entered the URL to my RSS feed from Flickr, and enabled the Image Grabber for this feed.

Once the Feed is created, the latest 20 images from the RSS feed are imported and 20 new Photos nodes are created. In the example below, the image with the 'Photo' label is the Image field mapped by the Image Grabber module. It is this image that I'll be displaying within my Gallery.

With the new Photo nodes created, I then created the View to display them.

The View selects the image within the Photo content type, and displays in it a grid using an ImageCache preset. The View is limited to 20 nodes per page, and uses a full pager if this is exceeded. The nodes are sorted by the descending post date, and filtered by whether or not they are published, and only to include Photo nodes.

As an additional effect, I also included the 'Feeds Item - Item Link' field, which is basically the original link from the RSS feed. By checking the box the exclude the item from the display, it is not shown, but makes the link available to be used elsewhere. By checking the box 'Re-write the output for this field' on the 'Content: Photo' field, I was able to add the replacement token (in this case, [url]) as the path for a link around each image. This meant that when someone clicked a thumbnail of a photo, they were directed to the Flickr website instead of the node within my Drupal site.
