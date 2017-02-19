---
title: Writing a .info file for a Drupal 7 theme
slug: writing-info-file-drupal-7-theme
tags:
  - theming
  - drupal-theming
  - drupal
  - code
use: [posts]
redirect:
    - blog/info-theme/drupal-7-theme/
---
{% block excerpt %}
An example .info file for a Drupal 7 theme.
{% endblock %}

{% block content %}
~~~
name = My Theme
description = A description of my theme
core = 7.x

# Add a base theme, if you want to use one.
base = mybasetheme

# Define regions, otherwise the default regions will be used.
regions[header] = Header
regions[navigation] = Navigation
regions[content] = Content
regions[sidebar] = Sidebar
regions[footer] = Footer

# Define which features are available. If none are specified, all the default 
# features will be available.
features[] = logo
features[] = name
features[] = favicon

# Add stylesheets
stylesheets[all][] = css/reset.css
stylesheets[all][] = css/mytheme.css
stylesheets[print][] = css/print.css

# Add javascript files
styles[] = js/mytheme.js
~~~
{% endblock %}
