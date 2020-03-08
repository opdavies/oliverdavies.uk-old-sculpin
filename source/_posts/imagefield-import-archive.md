---
title: Imagefield Import Archive
date: 2011-05-23
excerpt: I've finally uploaded my first module onto Drupal.org!
tags:
  - drupal-planet
  - imagefield-import
---
I've finally uploaded my first module onto Drupal.org!

I've written many custom modules, although the vast majority of them are either small tweaks for my own sites, or company/site-specific modules that wouldn't be good to anyone else, so there would be nothing achieved by contributing them back to the community. Previously, I've blogged about the [Imagefield Import](http://drupal.org/project/imagefield_import) module - a module that I use on a number of sites, both personal and for clients - and what I've looked for lately is for a way to speed up the process again. Gathering my images together and manually copying them into the import directory takes time - especially if I'm working somewhere with a slow Internet connection and I'm FTP-ing the images into the directory. Also, it's not always the easiest solution for site users - especially non-technical ones. 

So, I wrote the Imagefield Import Archive module. Including comments, the module contains 123 lines, and builds upon the existing functionality of the Imagefield Import module by adding the ability for the user to upload a .zip archive of images. The archive is then moved into the specified import directory and unzipped before being deleted, and the user is directed straight to the standard Imagefield Import page where their images are waiting to be imported, just as usual.

The module is currently a [sandbox project](http://drupal.org/sandbox/opdavies/1165110) on Drupal.org, although I have applied for full project access so that I can be added as a fully-fledged downloadable module.
