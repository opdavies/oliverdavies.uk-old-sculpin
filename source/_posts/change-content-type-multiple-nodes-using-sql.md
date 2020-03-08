---
title: Change the Content Type of Multiple Nodes Using SQL
date: 2010-07-01
excerpt: In this post, I will be changing values within my Drupal 6 site's database to quickly change the content type of multiple nodes.
tags:
  - drupal-planet
  - drupal-6
  - drupal
  - sql
  - sequel-pro
  - database
  - content-types
---
In this post, I will be changing values within my Drupal 6 site's database to quickly change the content type of multiple nodes. I will be using a test development site with the core Blog module installed, and converting Blog posts to a custom content type called 'News article'.

**Before changing any values within the database, ensure that you have an up-to-date backup which you can restore if you encounter a problem!**

To begin with, I created the 'News article' content type, and then used the Devel Generate module to generate some Blog nodes.

Using [Sequel Pro](http://www.sequelpro.com), I can query the database to view the Blog posts (you can also do this via the [Terminal](http://guides.macrumors.com/Terminal) in a Mac OS X/Linux, [Oracle SQL Developer](http://www.oracle.com/technology/software/products/sql/index.html) on Windows, or directly within [phpMyAdmin](http://www.phpmyadmin.net/home_page/index.php)):

Using an SQL 'Update' command, I can change the type value from 'blog' to 'article'. This will change every occurance of the value 'blog'. If I wanted to only change certain nodes, I could add a 'Where' clause to only affect nodes with a certain nid or title.

Now, when I query the database, the type is shown as 'article'.

Now, when I go back into the administration section of my site and view the content, the content type now shows at 'News article'.
