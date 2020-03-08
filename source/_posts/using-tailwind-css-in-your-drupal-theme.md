---
title: Using Tailwind CSS in your Drupal Theme
date: 2018-02-05
excerpt: What is Tailwind CSS, and how do I use it in Drupal theme?
tags:
    - drupal
    - drupal-planet
    - drupal-theming
    - tailwind-css
---
## What is Tailwind?

> Tailwind is a utility-first CSS framework for rapidly building custom user interfaces.

It generates a number of utility classes that you can add to your theme's markup to apply different styling, as well as the ability to apply classes to other markup and create components comprised of utility classes using a custom `@apply` PostCSS directive.

## Initial Configuration

The installation and configuration steps are essentially the same as those outlined within the [Tailwind documentation][1], and should be performed within your custom theme's directory (e.g. `sites/all/themes/custom/mytheme` for Drupal 7 or `themes/custom/mytheme` for Drupal 8:

1. Require PostCSS and Tailwind via `npm` or `yarn`.
1. Generate a configuration file using `./node_modules/.bin/tailwind init`.
1. Tweak the settings as needed.
1. Add a `postcss.config.js` file.
1. Configure your build tool (Gulp, Grunt, Webpack).
1. Generate the CSS.
1. Include a path to the generated CSS in your `MYTHEME.info`, `MYTHEME.info.yml` or `MYTHEME.libraries.yml` file.

## PostCSS Configuration

Create a `postcss.config.js` file and add `tailwindcss` as a plugin, passing the path to the config file:

```language-js
module.exports = {
    plugins: [
        require('tailwindcss')('./tailwind.js'),
    ]
}
```

## Configuration for Drupal

There are some configuration settings within `tailwind.js` that you’ll need to change to make things work nicely with Drupal. These are within the `options` section:

```language-js
options: {
    prefix: 'tw-',
    important: true,
    ...
}
```

### Prefix

By adding a prefix like `tw-`, we can ensure that the Tailwind classes don’t conflict with core HTML classes like `block`. We can also ensure that they won't conflict with any other existing HTML or CSS.

No prefix:

![](/images/blog/using-tailwind-drupal/prefix-1.png){.with-border}

With prefix:

![](/images/blog/using-tailwind-drupal/prefix-2.png){.with-border}

### Important

We can also set the `!important` rule on all Tailwind’s generated classes. We need to do this if we want to override core styles which have more specific rules.

For example: if I had this core markup then the left margin added by `tw-ml-4` would be overridden by core’s `.item-list ul` styling.

```language-html
<div class="item-list">
  <ul class="tw-ml-4">
    ...
  </ul>
</div>
```

![](/images/blog/using-tailwind-drupal/important-1.png){.with-border}

With the `!important` rule enabled though, the Tailwind’s class takes precedence and is applied.

![](/images/blog/using-tailwind-drupal/important-2.png){.with-border}

## Example

For an example of Tailwind within a Drupal 8 theme, see the custom theme for the [Drupal Bristol website][0] on GitHub.

[0]: https://github.com/drupalbristol/drupal-bristol-website/tree/master/web/themes/custom/drupalbristol
[1]: https://tailwindcss.com/docs/installation
[2]: https://www.npmjs.com/get-npm
[3]: https://yarnpkg.com/lang/en/docs/install
