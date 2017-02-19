---
title: Adding Custom Theme Templates in Drupal 7
description: >
  Today, I had a situation where I was displaying a list of teasers for news article nodes. The article content type had several different fields assigned to it, including main and thumbnail images. In this case, I wanted to have different output and fields displayed when a teaser was displayed compared to when a complete node was displayed.
slug: adding-custom-theme-templates-drupal-7
tags:
  - drupal-planet
  - drupal
use: [posts]
redirect:
    - blog/custom-theme-templates/
---
{% block excerpt %}
Today, I had a situation where I was displaying a list of teasers for news article nodes. The article content type had several different fields assigned to it, including main and thumbnail images. In this case, I wanted to have different output and fields displayed when a teaser was displayed compared to when a complete node was displayed.

I have previously seen it done this way by adding this into in a node.tpl.php file:
{% endblock %}

{% block content %}
Today, I had a situation where I was displaying a list of teasers for news article nodes. The article content type had several different fields assigned to it, including main and thumbnail images. In this case, I wanted to have different output and fields displayed when a teaser was displayed compared to when a complete node was displayed.

I have previously seen it done this way by adding this into in a node.tpl.php file:

~~~~
if ($teaser) {
  // The teaser output.
}
else {
  // The whole node output.
}
~~~~

However, I decided to do something different and create a separate template file just for teasers. This is done using the hook_preprocess_HOOK function that I can add into my theme's template.php file.

The function requires the node variables as an argument - one of which is theme_hook_suggestions. This is an array of suggested template files that Drupal looks for and attempts to use when displaying a node, and this is where I'll be adding a new suggestion for my teaser-specific template. Using the `debug()` function, I can easily see what's already there.

~~~~php
array (
  0 => 'node__article',
  1 => 'node__343',
  2 => 'node__view__latest_news',
  3 => 'node__view__latest_news__page',
)
~~~~

So, within my theme's template.php file:

~~~~php
/**
 * Implementation of hook_preprocess_HOOK().
 */
function mytheme_preprocess_node(&$variables) {
  $node = $variables['node'];
  
  if ($variables['teaser']) {
    // Add a new item into the theme_hook_suggestions array.
    $variables['theme_hook_suggestions'][] = 'node__' . $node->type . '_teaser';
  }
}
~~~~

After adding the new suggestion:

~~~~php
array (
  0 => 'node__article',
  1 => 'node__343',
  2 => 'node__view__latest_news',
  3 => 'node__view__latest_news__page',
  4 => 'node__article_teaser',
)
~~~~

Now, within my theme I can create a new node--article-teaser.tpl.php template file and this will get called instead of the node--article.tpl.php when a teaser is loaded. As I'm not specifying the node type specifically and using the dynamic <em>$node->type</em> value within my suggestion, this will also apply for all other content types on my site and not just news articles.
{% endblock %}
