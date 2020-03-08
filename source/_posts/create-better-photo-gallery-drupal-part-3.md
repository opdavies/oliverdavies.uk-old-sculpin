---
title: Create a Better Photo Gallery in Drupal - Part 3
date: 2010-10-13
excerpt: Grouping galleries by category.
tags:
  - drupal
---
The next part of the new gallery that I want to implement is to group the galleries by their respective categories. The first step is to edit my original photo_gallery view and add an additional display.

I've called it 'Taxonomy', and it's similar to the original 'All Galleries' view. The differences are that I've added the taxonomy term as an argument, removed the header, and updated the path to be `gallery/%`. The other thing that I need to do is overwrite the output of the original 'All Galleries' View by creating a file called `views-view--photo-gallery--page-1.tpl.php` and placing it within my theme directory.

Within that file, I can remove the standard content output. This still outputs the heading information from the original View. I can now use the function called 'views_embed_view' to embed my taxonomy display onto the display. The views_embed_view function is as follows:

```language-php
<?php views_embed_view('my_view', 'block_1', $arg1, $arg2); ?>
```

So, to display the galleries that are assigned the taxonomy of 'tournaments', I can use the following:

```language-php
<?php print views_embed_view('photo_gallery', 'page_2', 'tournaments'); ?>
```

To reduce the amount of code needed, I can use the following 'while' loop to generate the same code for each taxonomy term. It dynamically retrieves the relevant taxonomy terms from the database, and uses each name as the argument for the view.

```language-php
<?php
$terms = db_query("SELECT * FROM {term_data} WHERE vid = 1");
while ($term = db_fetch_array($terms)) {
  print '<h3>' . $term['name'] . '</h3>';
  print views_embed_view('gallery', 'page_2', $term['name']);
}
?>
```
