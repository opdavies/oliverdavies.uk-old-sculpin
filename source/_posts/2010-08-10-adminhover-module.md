---
title: Review of the Admin:hover Module
slug: review-adminhover-module
tags:
  - drupal-planet
  - drupal-6
  - drupal-modules
  - admin:hover
  - administration
use: [posts]
redirect:
    - blog/review-adminhover-module/
---
Today, I'd like to just give a quick overview of the [Admin:hover](http://drupal.org/project/admin_hover) module. It basically adds an administrative menu that pops up when you hover over a node or block within your Drupal website - the kind of functionality that was present within previous versions of the [Admin module](http://drupal.org/project/admin). It also integrates well with the [Devel](http://drupal.org/project/devel) and [Clone](http://drupal.org/project/node_clone) modules.

I've found this to be extremely useful whilst working on photo galleries etc. where multiple nodes are displayed in a grid format and I quickly need to publish or unpublish something for testing purposes. No longer do I need to open each node, or go into the administration area to perform the required actions.

It is also possible to customise which links are available from within the adminstration area. The possible selections that I currently have on this site are as follows:

**Node links:**

* Edit
* Publish
* Unpublish
* Promote
* Unpromote
* Make sticky
* Make unsticky
* Delete
* Clone
* Dev load
* View author
* Edit author
* Add

**Block links:**

* Configure block
* Add block

Although, as I have additional contributed modules installed, some of these may not neccassaily be available out of the box.
