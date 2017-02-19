---
title: How to Create and Apply Patches
slug: create-and-apply-patches
tags:
  - drupal-planet
  - drupal-6
  - modules
  - patches
use: [posts]
redirect:
    - blog/create-apply-patches/
---
{% block excerpt %}
Earlier this year, I posted a solution to [an issue](http://drupal.org/node/753898) on the Drupal.org issue queue. Originally, I just posted the code back onto the issue, but have now created a patch that can easily be applied to any Drupal 6 installation. Here is a run-through of the process of creating and applying a patch. In this case, I made changes to the `user_pass_validate()` function that's found within `modules/user/user.pages.inc`.
{% endblock %}

{% block content %}
To begin with, a download a fresh copy of Drupal 6.19 and created a copy of the original user.pages.inc file. Within the duplicate file, I made the same changes to the function that I did in earlier code, and saved the changes. Now, within my Terminal, I can navigate to Drupal's root directory and create the patch.

    diff -rup modules/user/user.pages.inc modules/user/user.pages2.inc > /Users/oliver/Desktop/different_messages_for_blocked_users.patch

This command compares the differences between the two files, and creates the specified patch file.

To apply the patch to my Drupal installation, I go back to Terminal and run the following code:

    patch -p0 < /Users/oliver/Desktop/different_messages_for_blocked_users.patch

If, for some reason, I need to reverse the patch, I can run this code:

    patch -p0 -R < /Users/oliver/Desktop/different_messages_for_blocked_users.patch

And that's it!

There is also a Git patch creation workflow, which is described at <http://groups.drupal.org/node/91424>. Thanks to [Randy Fay](http://randyfay.com) for making me aware of this, and suggesting a slight change to my original patch creation command.
{% endblock %}
