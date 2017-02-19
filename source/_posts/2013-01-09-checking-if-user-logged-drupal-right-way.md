---
title: Checking if a user is logged into Drupal (the right way)
description: How to check if a user is logged into Drupal by using the user_is_logged_in() and user_is_anonymous() functions.
slug: checking-if-user-logged-drupal-right-way
tags:
  - drupal
  - drupal-6
  - drupal-7
  - drupal-planet
  - php
use: [posts]
redirect:
    - blog/quickest-way-install-sublime-text-2-ubuntu/
---
{% block excerpt %}
I see this regularly when working on Drupal sites when someone wants to check whether the current user is logged in to Drupal (authenticated) or not (anonymous).
{% endblock %}

{% block content %}
I see this regularly when working on Drupal sites when someone wants to check whether the current user is logged in to Drupal (authenticated) or not (anonymous):

~~~~
global $user;
if ($user->uid) {
  // The user is logged in.
}
~~~~

or

~~~~
global $user;
if (!$user->uid) {
  // The user is not logged in.
}
~~~~

The better way to do this is to use the [user_is_logged_in()](http://api.drupal.org/api/drupal/modules!user!user.module/function/user_is_logged_in/7) function.

~~~~
if (user_is_logged_in()) {
  // Do something.
}
~~~~

This returns a boolean (TRUE or FALSE) depending or not the user is logged in. Essentially, it does the same thing as the first example, but there's no need to load the global variable.

A great use case for this is within a `hook_menu()` implementation within a custom module.

~~~~
/**
 * Implements hook_menu().
 */
function mymodule_menu() {
  $items['foo'] = array(
    'title' => 'Foo',
    'page callback' => 'mymodule_foo',
    'access callback' => 'user_is_logged_in',
  );

  return $items;
}
~~~~

There is also a [user_is_anonymous()](http://api.drupal.org/api/drupal/modules!user!user.module/function/user_is_anonymous/7) function if you want the opposite result. Both of these functions are available in Drupal 6 and higher.
{% endblock %}
