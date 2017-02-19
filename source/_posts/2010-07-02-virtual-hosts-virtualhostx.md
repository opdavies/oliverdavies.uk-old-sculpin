---
title: Create Virtual Hosts on Mac OS X Using VirtualHostX
slug: create-virtual-hosts-mac-os-x-using-virtualhostx
tags:
  - drupal-planet
  - drupal-6
  - mamp
  - virtual-hosts
  - virtualhostx
use: [posts]
redirect:
    - blog/create-virtual-hosts-mac-os-x-using-virtualhostx/
---
This isn't a Drupal related topic per se, but it is a walk-through of one of the applications that I use whilst doing Drupal development work. I assume, like most Mac OS X users, I use [MAMP](http://www.mamp.info/en/index.html) to run Apache, MySQL and PHP locally whilst developing. I also use virtual hosts in Apache to create local .dev domains which are as close as possible to the actual live domains. For example, if I was developing a site called mysite.com, my local development version would be mysite.dev.

Normally, I would have to edit the hosts file and Apache's httpd.conf file to create a virtual host. The first to set the domain and it's associated IP address, and the other to configure the domain's directory, default index file etc. However, using [VirtualHostX](http://clickontyler.com/virtualhostx), I can quickly create a virtual host without having to edt any files. Enter the virtual domain name, the local path and the port, and apply the settings. VirtualHostX automatically restarts Apache, so the domain is ready to work straight away. You can also enter custom directives from within the GUI.

There's also an option to share the host over the local network. Next, I intend on configuring a virtual Windows PC within VMware Fusion to view these domains so that I can do cross-browser testing before putting a site live.

I ensured that my Apache configuration within MAMP was set to port 80, and that VirtualHostX was using Apache from MAMP instead of Apple's built-in Apache.

**Note:** One problem that I had after setting this up, was that I was receving an error when attempting to open a Drupal website which said *'No such file or directory'.*

After some troubleshooting, I found out that Web Sharing on my Mac had become enabled (I don't know why, I've never enabled it), and that this was causing a conflict with Apache. Once I opened my System Preferences and disabled it, everything worked fine!

This, along with [MAMP](http://www.mamp.info/en/index.html), [Coda](http://www.panic.com/coda), [Sequel Pro](http://www.sequelpro.com), and [Transmit](http://www.panic.com/transmit), has become an essential tool within my development environment.
