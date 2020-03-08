---
title: Create a Better Photo Gallery in Drupal - Part 2
date: 2010-08-17
excerpt: Updating the galleries’ created and modified dates.
tags:
  - drupal-planet
  - drupal-6
  - photo-gallery
  - sql
  - sequel-pro
---
At the end of my last post, I'd finished creating the first part of  the new photo gallery, but I wanted to change the dates of the published  photos to reflect the ones on the client's original website.

Firstly, I'll refer to the previous list of published galleries that I  created before, and create something different that also displays the created and modified dates. Picking the node ID of the required gallery,  I used the following SQL query to display a list of photos.

```language-sql
SELECT n.title, n.nid, n.created, n.changed, p.field_gallery_nid
FROM node n, content_type_photo pWHERE n.type = 'photo'
AND p.field_gallery_nid = 103AND n.nid = p.nid
ORDER BY n.nid ASC;
```

When I look back at the old photo gallery, I can see that the previous 'last added' date was June 27, 2008. So, how do I update my new photos  to reflect that date? Using <http://www.onlineconversion.com/unix_time.htm>,  I can enter the required date in its readable format, and it will give me the equivilent UNIX timestamp. To keep things relatively simple, I'll  set all photos within this gallery to the same time.

The result that I'm given is '1217149200'. I can now use an UPDATE statement within another SQL query to update the created and modified  dates.

```language-sql
UPDATE node
INNER JOIN content_type_photo
ON node.nid = content_type_photo.nid
SET
  node.created = 1217149200,
  node.changed = 1217149200
WHERE content_type_photo.field_gallery_nid = 103
```

Now when I query the database, both the created and modified dates  have been updated, and when I return to the new photo gallery, the  updated value is being displayed.

Once the changes have been applied, it's a case of repeating the above process for each of the required galleries.

In the next post, I'll explain how to add a count of published galleries and photos on the main photo gallery page, as well as how to  install and configure the [Shadowbox](http://drupal.org/project/shadowbox) module.
