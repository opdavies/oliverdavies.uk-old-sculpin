---
title: Configuring the Reroute Email Module
tags:
  - drupal
  - drupal-6
  - drupal-7
  - drupal-planet
  - email
draft: true
use: [posts]
redirect:
    - blog/configuring-the-reroute-email-module/
---
{% block excerpt %}
[Reroute Email](https://www.drupal.org/project/reroute_email) module uses `hook_mail_alter()` to prevent emails from being sent to users from non-production sites. It allows you to enter one or more email addresses that will receive the emails instead of delivering them to the original user.
{% endblock %}

{% block content %}
[Reroute Email](https://www.drupal.org/project/reroute_email) module uses `hook_mail_alter()` to prevent emails from being sent to users from non-production sites. It allows you to enter one or more email addresses that will receive the emails instead of delivering them to the original user.

> This is useful in case where you do not want email sent from a Drupal site to reach the users. For example, if you copy a live site to a test site for the purpose of development, and you do not want any email sent to real users of the original site. Or you want to check the emails sent for uniform formatting, footers, ...etc.

As we don't need the module configured on production (we don't need to reroute any emails there), it's best to do this in code using settings.local.php (if you have one) or the standard settings.php file.

The first thing that we need to do is to enable rerouting. Without doing this, nothing will happen.

~~~php
$conf['reroute_email_enable'] = TRUE;
~~~

The next option is to whether to show rerouting description in mail body. I usually have this enabled. Set this to TRUE or FALSE depending on your preference.

~~~php
$conf['reroute_email_enable_message'] = TRUE;
~~~

The last setting is the email address to use. If you're entering a single address, you can add it as a simple string.

~~~php
$conf['reroute_email_address'] = 'person1@example.com';
~~~

In this example, all emails from the site will be rerouted to person1@example.com.

If you want to add multiple addresses, these should be added in a semicolon-delimited list. Whilst you could add these also as a string, I prefer to use an array of addresses and the `implode()` function.

~~~php
$conf['reroute_email_address'] = implode(';', array(
  'person1@example.com',
  'person2@example.com',
  'person3@example.com',
));
~~~

In this example, person2@example.com and person3@example.com would receive their emails from the site as normal. Any emails to addresses not in the array would continue to be redirected to person1@example.com.
{% endblock %}
