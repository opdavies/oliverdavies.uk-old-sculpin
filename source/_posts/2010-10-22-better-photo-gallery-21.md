---
title: Create a Better Photo Gallery in Drupal - Part 2.1
slug: create-better-photo-gallery-drupal-part-21
tags:
  - drupal
use: [posts]
redirect:
    - blog/better-photo-gallery-21/
---
Today, I realised that I hadn't published the code that I used to create the total figures of galleries and photos at the top of the gallery (I said at the end of [Part 2](/blog/create-better-photo-gallery-drupal-part-2/ "Create a Better Photo Gallery in Drupal - Part 2") that I'd include it in [Part 3](/blog/create-better-photo-gallery-drupal-part-3/ "Create a Better Photo Gallery in Drupal - Part 3"), but I forgot). So, here it is:

~~~php
<?php

// Queries the database and returns a list of nids of published galleries.
$galleries = db_query("SELECT nid FROM {node} WHERE type = 'gallery' AND status = 1");
// Resets the number of photos.
$output = 0;
// Prints a list of nids of published galleries.
while($gallery = db_fetch_array($galleries)) {
  $gallery_id = $gallery['nid'];
  $photos = $photos + db_result(db_query("SELECT COUNT(*) FROM node n, content_type_photo ctp WHERE n.status = 1 AND n.type = 'photo' AND ctp.field_gallery_nid = $gallery_id AND n.nid = ctp.nid"));
}
  
// Prints the output.
print 'There ';
if($photos == 1) {
  print 'is';
} 
else {
  print 'are';
}
print ' currently ';
print $photos . ' ';
if($photos == 1) {
  print 'photo';
} 
else {
  print 'photos';
} 
print ' in ';
  
// Counts the number of published galleries on the site.
$galleries = db_result(db_query("SELECT COUNT(*) FROM {node} WHERE TYPE = 'gallery' AND STATUS = 1"));
  
// Prints the number of published galleries.
print $galleries;
if ($galleries == 1) {
  print ' gallery';
} 
else {
  print ' galleries';
}
print '.';
?>
~~~

It was applied to the view as a header which had the input format set to PHP code.
