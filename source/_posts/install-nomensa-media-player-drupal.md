---
title: Install and Configure the Nomensa Accessible Media Player in Drupal
date: 2012-07-14
excerpt: This week I released the first version of the Nomensa Accessible Media Player module for Drupal 7. Here's some instructions of how to install and configure it.
tags:
  - accessibility
  - drupal
  - drupal-planet
  - nomensa
---
This week I released the first version of the Nomensa Accessible Media Player module for Drupal 7. Here's some instructions of how to install and configure it.

*The official documentation for this module is now located at <https://www.drupal.org/node/2383447>. This post was accurate at the time of writing, whereas the documentation page will be kept up to date with any future changes.*

## Initial configuration

### Download the Library

The library can be downloaded directly from GitHub, and should be placed within you *sites/all/libraries/nomensa_amp* directory.
 
```language-bash
drush dl libraries nomensa_amp
git clone https://github.com/nomensa/Accessible-Media-Player sites/all/libraries/nomensa_amp
cd sites/all/libraries/nomensa_amp
rm -rf Accessible-media-player_2.0_documentation.pdf example/ README.md
drush en -y nomensa_amp
```

### Configure the Module

Configure the module at <em>admin/config/media/nomensa-amp</em> and enable the players that you want to use.

## Adding videos

Within your content add links to your videos. For example:

### YouTube

```language-html
<a href="http://www.youtube.com/watch?v=Zi31YMGmQC4">Checking colour contrast</a>
```

### Vimeo

```language-html
<a href="http://vimeo.com/33729937">Screen readers are strange, when you're a stranger by Leonie Watson</a>
```

## Adding captions

The best way that I can suggest to do this is to use a File field to upload your captions file:

1. Add a File field to your content type;
1. On your page upload the captions file.
1. Right-click the uploaded file, copy the link location, and use this for the path to your captions file.

For example:

```language-html
<a href="http://www.youtube.com/watch?v=Zi31YMGmQC4">Checking colour contrast</a> <a class="captions" href="http://oliverdavies.co.uk/sites/default/files/checking-colour-contrast-captions.xml">Captions for Checking Colour Contrast</a>
```

## Screencast

<div class="embed-container">
    <iframe
        src="https://player.vimeo.com/video/45731954"
        width="500"
        height="313"
        frameborder="0"
        webkitallowfullscreen
        mozallowfullscreen
        allowfullscreen>
    </iframe>
</div>
