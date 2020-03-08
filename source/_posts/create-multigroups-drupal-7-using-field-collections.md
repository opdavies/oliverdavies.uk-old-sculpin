---
title: Create Multigroups in Drupal 7 using Field Collections
date: 2011-08-28
excerpt: How to replicate CCK’s multigroups in Drupal 7 using the Field Collections module.
tags:
  - drupal-7
  - drupal-planet
  - cck
  - fields
  - field-collection
  - entity-api
  - multigroup
---
One of my favourite things lately in Drupal 6 has been CCK 3, and more specifically, the Content Multigroups sub-module. Basically this allows you to create a fieldset of various CCK fields, and then repeat that multiple times. For example, I use it on this site whist creating invoices for clients. I have a fieldset called 'Line Item', containing 'Description', 'Quantity' and 'Price' fields. With a standard fieldset, I could only have one instance of each field - however, using a multigroup, I can create multiple groups of line items which I then use within the invoice.

But at the time of writing this, there is no CCK 3 version for Drupal 7. So, I created the same thing using [Field Collection](http://drupal.org/project/field_collection) and [Entity](http://drupal.org/project/entity) modules.

With the modules uploaded and enabled, go to admin/structure/field-collections and create a field collection.

With the module enabled, you can go to your content type and add a Field Collection field. By default, the only available Widget type is 'Hidden'.

Next, go to admin/structure/field-collections and add some fields to the field collection - the same way that you would for a content type. For this collection is going to contain two node reference fields - Image and Link.

With the Field Collection created, I can now add it as a field within my content type.

Whilst this works perfectly, the field collection is not editable from the node edit form. You need to load the node, and the collection is displayed here with add, edit, and delete buttons. This wasn't an ideal solution, and I wanted to be able to edit the fields within the collection from the node edit form - the same way as I can using multigroups in Drupal 6.

After some searching I found [a link to a patch](http://drupal.org/node/977890#comment-4184524) which when applied adds a 'subform' widget type to the field collection field and allows for it to be embedded into, and editable from within the node form. Going back to the content type fields page, and clicking on 'Hidden' (the name of the current widget), I can change it to subform and save my changes.

With this change applied, when I go back to add or edit a node within this content type, my field collection will be easily editable directly within the form.
