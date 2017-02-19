---
title: Programmatically Load an Entityform in Drupal 7
tags:
    - drupal
    - drupal-7
    - drupal-planet
    - entityform
meta:
    description: How to programmatically load, render and embed an entityform in Drupal 7.
use: [posts]
redirect:
    - blog/entityform/
---
{% block excerpt %}
I recently had my first experience using the [Entityform module](https://www.drupal.org/project/entityform) in a project. It was quite easy to configure with different form types, but then I needed to embed the form into an overlay. I was expecting to use the `drupal_get_form()` function and render it, but this didn’t work.

Here are the steps that I took to be able to load, render and embed the form.
{% endblock %}

{% block content %}
I recently had my first experience using the [Entityform module](https://www.drupal.org/project/entityform) in a project. It was quite easy to configure with different form types, but then I needed to embed the form into an overlay. I was expecting to use the `drupal_get_form()` function and render it, but this didn’t work.

Here are the steps that I took to be able to load, render and embed the form.

## Loading the Form

The first thing that I needed to do to render the form was to load an empty instance of the entityform using `entityform_empty_load()`. In this example, `newsletter` is the name of my form type.

```php
$form = entityform_empty_load('newsletter');
```

This returns an instance of a relevant `Entityform` object.

## Rendering the Form

The next step was to be able to render the form. I did this using the `entity_form_wrapper()` function.

As this function is within the `entityform.admin.inc` file and not autoloaded by Drupal, I needed to include it using `module_load_include()` so that the function was available.

```php
module_load_include('inc', 'entityform', 'entityform.admin');
$output = entityform_form_wrapper($form, 'submit', 'embedded'),
```

The first argument is the `Entityform` object that was created in the previous step (I’ve [submitted a patch](https://www.drupal.org/node/2639584) to type hint this within entityform so that it’s clearer what is expected), which is required.

The other two arguments are optional. The second argument is the mode (`submit` is the default value), and the last is the form context. `page` is the default value, for use on the submit page, however I changed this to `embedded`.

I could then pass this result into my theme function to render it successfully within the relevant template file.

## Resources

* [The entityform module](https://www.drupal.org/project/entityform)
* [My issue and patch to add the type hint to the entityform_form_wrapper function](https://www.drupal.org/node/2639584)
{% endblock %}
