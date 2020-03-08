---
title: Prevent Apache from displaying text files within a web browser
date: 2012-05-23
excerpt: How to prevent Apache from displaying the contents of files like CHANGELOG.txt.
tags:
  - apache
  - code
  - drupal
---
When you download [Drupal](http://drupal.org/project/drupal), there are several text files that are placed in the root of your installation. You don't want or need these to be visible to anyone attempting to view them in a browser - especially CHANGELOG.txt as that includes the exact version of Drupal you are running and could therefore have security implications.

Rather than delete these files or change the file permissions manually for each file, I can add the following lines into my VirtualHost configuration.

```language-apacheconf
<Files ~ "\.txt$">
    Order deny,allow
    Deny from all
</Files>
```

This prevents any files with a .txt extension from being accessed and rendered in a web browser.
