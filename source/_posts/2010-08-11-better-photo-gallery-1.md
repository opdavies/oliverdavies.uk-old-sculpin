---
title: Create a Better Photo Gallery in Drupal - Part 1
slug: create-better-photo-gallery-drupal-part-1
tags:
  - drupal-planet
  - drupal-6
  - photo-gallery
  - sql
  - views
  - sequel-pro
  - cck
  - views-attach
  - drupal
use: [posts]
redirect:
    - blog/create-better-photo-gallery-drupal-part-1/
---
Recently, I converted a client's static HTML website, along with their Coppermine Photo Gallery, into a Drupal-powered website.

Over the next few posts, I'll be replicating the process that I used during the conversion, and how I added some additional features to my Drupal gallery.

To begin with, I created my photo gallery as described by [Jeff Eaton](http://www.lullabot.com/about/team/jeff-eaton) in [this screencast](http://www.lullabot.com/articles/photo-galleries-views-attach), downloaded all my client's previous photos via FTP,  and quickly added them into the new gallery using the [Imagefield Import](http://drupal.org/project/imagefield_import) module (which I mentioned [previously](/blog/quickly-import-multiples-images-using-imagefieldimport-module/)).

When I compare this to the previous gallery, I can see several  differences which I'd like to include. The first of which is the number  of photos in each gallery, and the date that the most recent photo was added.

To do this, I'd need to query my website's database. To begin with, I  wanted to have a list of all the galleries on my site which are  published, and what they're unique node ID values are. To do this, I  opened Sequel Pro and entered the following code:

~~~sql
SELECT title 
AS title, nid 
AS gallery_idFROM node
WHERE type = 'gallery'
AND status = 1;
~~~

As the nid value of each gallery corresponds with the 'field_gallery_nid' field within the content_type_photo field, I can now  query the database and retrieve information about each specific  gallery.

For example, using [aliasing](http://www.w3schools.com/sql/sql_alias.asp) within my SQL statement, I can retrieve a list of all the published  photos within the 'British Squad 2008' gallery by using the following code:

~~~sql
SELECT n.title, n.nid, p.field_gallery_nid
FROM node n, content_type_photo p
WHERE p.field_gallery_nid = 105
AND n.status = 1
AND n.nid = p.nid;
~~~

I can easily change this to count the number of published nodes by changing the first line of the query to read SELECT COUNT(*).

~~~sql
SELECT COUNT(*)
FROM node n, content_type_photo p
WHERE p.field_gallery_nid = 105
AND n.status = 1
AND n.nid = p.nid;
~~~

As I've used the [Views Attach](http://drupal.org/project/views_attach) module, and I'm embedding the photos directly into the Gallery nodes, I  easily add this to each gallery by creating a custom  node-gallery.tpl.php file within my theme. I can then use the following PHP code to retrieve the node ID for that specific gallery:

~~~php
<?php
$selected_gallery = db_result(db_query("
SELECT nid 
FROM {node} 
WHERE type = 'gallery' 
AND title = '$title'
"));
?>
~~~

I can then use this variable as part of my next query to count the number of photos within that gallery, similar to what I did earlier.

~~~php
<?php
$gallery_total = db_result(db_query("
SELECT COUNT(*) 
FROM {content_type_photo} 
WHERE field_gallery_nid = $selected_gallery
"));
?>
~~~

Next, I wanted to display the date that the last photo was displayed within each album. This was done by using a similar query that also sorted the results in a descending order, and limited it to one result - effectively only returning the created date for the newest photo.

~~~php
<?php
$latest_photo = db_result(db_query("
SELECT n.created 
FROM {node} n, {content_type_photo} p 
WHERE p.field_gallery_nid = $selected_gallery 
AND n.nid = p.nid 
ORDER BY n.created DESC LIMIT 1
"));
?>
~~~

This was all then added into a 'print' statement which displayed it into the page.

~~~php
<?php
if ($selected_gallery_total != 0) {
  $output = '<i>There are currently ' . $selected_gallery_total . ' photos in this gallery.';
  $output .= 'Last one added on ' . $latest_photo . '</i>';
  print $output;
}
?>
~~~

OK, so let's take a look at the Gallery so far:

You will notice that the returned date value for the latest photo  added is displaying the UNIX timestamp instead of in a more readable format. This can be changed by altering the 'print' statement to include  a PHP 'date' function:

~~~php
<?php
if ($selected_gallery_total != 0) { 
  $output = '<i>There are currently ' . $selected_gallery_total . ' photos in this gallery.'; 
  $output .= 'Last one added on ' . date("l, jS F, Y", $latest_photo) . '.</i>';
  print $output;
}
?>
~~~

The values that I've entered are from [this page](http://php.net/manual/en/function.date.php) on PHP.net, and can be changed according on how you want the date to be displayed.

As I've added all of these photos today, then the correct dates are  being displayed. However, on the client's original website, the majority  of these photos were pubished several months or years ago, and I'd like  the new website to still reflect the original created dates. As opposed  to modifying each individual photograph, I'll be doing this in bulk in  my next post.
