---
title: Using Remote Files when Developing Locally with Stage File Proxy Module
tags:
  - drupal
  - drupal-planet
  - servers
use: [posts]
redirect:
    - blog/using-remote-files-when-developing-locally-stage-file-proxy-module/
---
{% block excerpt %}
How to install and configure the [Stage File Proxy](https://www.drupal.org/project/stage_file_proxy) module to serve remote images on your local Drupal site.
{% endblock %}

{% block content %}
Download the [Stage File Proxy](https://www.drupal.org/project/stage_file_proxy) module from Drupal.org and enable it on your site.

As this module is only going to be needed on pre-production sites, it would be better to configure this within your settings.php or settings.local.php file. We do this using the `$conf` array which removes the need to configure the module through the UI and store the values in the database.

~~~php
// File proxy to the live site.
$conf['stage_file_proxy_origin'] = 'http://www.example.com';

// Don't copy the files, just link to them.
$conf['stage_file_proxy_hotlink'] = TRUE;

// Image style images are the wrong size otherwise.
$conf['stage_file_proxy_use_imagecache_root'] = FALSE;
~~~

If the origin site is not publicly accessible yet, maybe it's a pre-live or staging site, and protected with a basic access authentication, you can include the username and password within the origin URL. 

~~~php
$conf['stage_file_proxy_origin'] = 'http://user:password@prelive.example.com';
~~~
{% endblock %}
