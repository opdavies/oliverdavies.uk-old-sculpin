---
title: Install and Configure the Nomensa Accessible Media Player in Drupal
slug: install-nomensa-media-player-drupal
tags:
  - accessibility
  - drupal
  - drupal-planet
  - nomensa
use: [posts]
redirect:
    - blog/install-nomensa-media-player-drupal/
---
{% block excerpt %}
This week I released the first version of the Nomensa Accessible Media Player module for Drupal 7. Here's some instructions of how to install and configure it.
{% endblock %}

{% block content %}
*The official documentation for this module is now located at <https://www.drupal.org/node/2383447>. This post was accurate at the time of writing, whereas the documentation page will be kept up to date with any future changes.*

This week I released the first version of the Nomensa Accessible Media Player module for Drupal 7. Here's some instructions of how to install and configure it.

## Initial configuration

### Download the Library

The library can be downloaded directly from GitHub, and should be placed within you *sites/all/libraries/nomensa_amp* directory.
 
~~~~
drush dl libraries nomensa_amp
git clone https://github.com/nomensa/Accessible-Media-Player sites/all/libraries/nomensa_amp
cd sites/all/libraries/nomensa_amp
rm -rf Accessible-media-player_2.0_documentation.pdf example/ README.md
drush en -y nomensa_amp
~~~~

### Configure the Module

Configure the module at <em>admin/config/media/nomensa-amp</em> and enable the players that you want to use.

## Adding videos

Within your content add links to your videos. For example:

### YouTube

    <a href="http://www.youtube.com/watch?v=Zi31YMGmQC4">Checking colour contrast</a>

### Vimeo

    <a href="http://vimeo.com/33729937">Screen readers are strange, when you're a stranger by Leonie Watson</a>

## Adding captions

The best way that I can suggest to do this is to use a File field to upload your captions file:

1. Add a File field to your content type;
2. On your page upload the captions file.
3. Right-click the uploaded file, copy the link location, and use this for the path to your captions file.

For example:
    
    <a href="http://www.youtube.com/watch?v=Zi31YMGmQC4">Checking colour contrast</a> <a class="captions" href="http://oliverdavies.co.uk/sites/default/files/checking-colour-contrast-captions.xml">Captions for Checking Colour Contrast</a>

## Screencast

<iframe src="https://player.vimeo.com/video/45731954" width="500" height="313" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
{% endblock %}
