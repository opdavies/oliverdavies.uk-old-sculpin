---
title: Conditional Email Addresses in a Webform
slug: conditional-email-addresses-webform
tags:
  - drupal-planet
  - drupal-6
  - conditional-email
  - webform
use: [posts]
redirect:
    - blog/conditional-email-addresses-webform/
---
I created a new Webform to serve as a simple Contact form, but left the main configuration until after I created the form components. I added 'Name', 'Email', 'Subject' and 'Message' fields, as well as a 'Category' select list. Below 'Options', I entered each of my desired options in the following format:

    Email address|Visible name

I went back to the form configuration page and expanded 'Conditional Email Recipients', and selected my Category. Note that the standard 'Email To' field above it needs to be empty. Originally, I made the mistake of leaving addresses in that field which resulted in people being sent emails regardles of which category was selected. I then configured the rest of the form.

Then, when I went to the finished form, the category selection was available.
