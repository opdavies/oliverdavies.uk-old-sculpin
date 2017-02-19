---
title: Forward one domain to another using mod_rewrite and .htaccess
slug: forward-one-domain-another-using-modrewrite-and-htaccess
tags:
  - .htaccess
  - code
  - drupal
  - apache
  - mod_rewrite
use: [posts]
redirect:
    - blog/forward-domain-mod-rewrite/
---
{% block excerpt %}
How to use the .htaccess file to forward to a different domain.
{% endblock %}

{% block content %}
Within the mod_rewrite section of your .htaccess file, add the following lines:

    RewriteCond %{HTTP_HOST} ^yoursite\.co\.uk$
    RewriteRule (.*) http://yoursite.com/$1 [R=301,L]

This automatically forwards any users from http://yoursite.co.uk to http://yoursite.com. This can also be used to forward multiple domains:

    RewriteCond %{HTTP_HOST} ^yoursite\.co\.uk$ [OR]
    RewriteCond %{HTTP_HOST} ^yoursite\.info$ [OR]
    RewriteCond %{HTTP_HOST} ^yoursite\.biz$ [OR]
    RewriteCond %{HTTP_HOST} ^yoursite\.eu$
    RewriteRule (.*) http://yoursite.com/$1 [R=301,L]

If any of the RewriteCond conditions apply, then the RewriteRule is executed.
{% endblock %}
