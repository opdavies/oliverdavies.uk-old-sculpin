---
title: Style Drupal 6's Taxonomy Lists with PHP, CSS and jQuery
slug: style-drupal-6s-taxonomy-lists-php-css-and-jquery
tags:
  - drupal-6
  - drupal-planet
  - drupal-theming
  - taxonomy
use: [posts]
redirect:
    - blog/style-drupal-6s-taxonomy-lists-php-css-and-jquery/
---
Whilst developing this, and other Drupal websites for clients, I decided that I wanted to categorise content using the taxonomy system. However, I wasn't happy with the way that Drupal displayed the terms lists by default, and I started comparing this to other websites that I look at. 

To start with, I wanted to have something that described what the list was displaying - like in the second example above. I wanted to have the words 'Posted in' displayed before the list of terms. To do this, I had to edit the node template file that exists within my theme folder (sites/all/themes). As I only wanted this change to affect my Blog posts, the file that I needed to change is **node-blog.tpl.php**

I scrolled down until I found the piece of code that displayed the terms list:

~~~php
<?php if ($terms): ?>
  <div class="terms terms-inline">
    <?php print t('Posted in') . $terms; ?>
  </div>
<?php endif; ?>
~~~

Adding `print t(' Posted in ')` will print the words 'Posted in' before outputing the terms.

I then added some CSS to re-size the spacing between the items, and then add the commas between them to seperate them:

~~~css
.terms ul.links li {
  margin-right: 1px;
  padding: 0;
}

.terms ul.links li:after {
  content: ",";
}

.terms ul.links li.last:after {
  content: ".";
}
~~~

I created a file named **script.js** in my theme folder with the following code in it. After clearing Drupal's caches, this file is automatically recognised by Drupal 6.

~~~js
if (Drupal.jsEnabled) {
  $(document).ready(function() {
    $('.terms ul.links li.last').prev().addClass('test');
  })
}
~~~

This code finds the last item in the list, uses **.prev** to select the one before it, and then uses **.addClass** to assign it the HTML class of "test". We can then use this class to target it with specific CSS.

~~~css
.terms ul.links li.test:after {
  content: " and";
}
~~~
