---
title: Restructuring my tailwind.js configuration files
date: 2019-03-08
excerpt: How I’ve started structuring my tailwind.js configuration files in preparation for Tailwind 1.0.
tags:
    - laravel-mix
    - tailwind-css
---
After watching Adam Wathan’s recent ["Working on Tailwind 1.0" YouTube video](https://www.youtube.com/watch?v=SkTKN38wSEM) and seeing some of the proposed changes to the `tailwind.js` configuration file, I’ve started to structure my current config files a little differently in preparation for 1.0.

## The current tailwind.js file format

Currently when you run `tailwind init` to create a new config file, it includes all of Tailwind’s default values, and then you can add, edit and remove values as needed.

Some values like colours, font families, plugins and modules you are likely to change for each project, whilst others like shadows, leading, z-index and opacity, you’re less likely to need to change.

It’s 952 lines including comments, which is quite long and could potentially be daunting for new Tailwind users.

The contents of the full file can be found in the [Tailwind CSS documentation](https://tailwindcss.com/docs/configuration#default-configuration), or it can be found in [various GitHub repositories](https://github.com/tailwindcss/plugin-examples/blob/master/tailwind.js).

## A preview of the new tailwind.js file format

In Adam’s [Laracon Online](https://laracon.net) talk, Tailwind CSS by Example, he showed the new configuration file format. Here is a snippet:

```js
module.exports {
  theme: {
    extend: {
      spacing: {
        7: '1.75rem',
      },
    },
    colors: {
      white: {
        default: '#fff',
        20: 'rgba(255,255,255,.2)',
        40: 'rgba(255,255,255,.4)',
        60: 'rgba(255,255,255,.6)',
        80: 'rgba(255,255,255,.8)',
      },
      ...
    }
    ...
  }
}
```

You’ll notice that the structure of the file is quite different, and that all of the default values have been removed and are now maintained by Tailwind itself.

This means that the configuration file contains only your custom changes, where you've either overridden a default value (e.g. colours) or added your own using `extend` (e.g. adding another spacing value, as in this example).

I think that's a great improvement and makes the file so much cleaner, and easier to read and understand.

## An interim approach

If you don’t want to wait until 1.0, or potentially 0.8, you can get some of this functionality now by restructuring your Tailwind configuration file.

Here is the complete `tailwind.js` file for the [DrupalCamp Bristol 2019 static landing page](https://dcb-2019-static.netlify.com), which uses Tailwind in addition to the existing traditional CSS:

```js
let defaultConfig = require('tailwindcss/defaultConfig')()

var colors = {
  ...defaultConfig.colors,
  black: '#000',
}

module.exports = {
  ...defaultConfig,
  colors: colors,
  textColors: colors,
  backgroundColors: colors,
  borderColors: Object.assign({ default: colors['grey-light'] }, colors),
  plugins: [
    require('tailwindcss-interaction-variants')(),
    require('tailwindcss-spaced-items'),
  ],
  modules: {
    ...defaultConfig.modules,
    textStyle: [...defaultConfig.modules.textStyle, 'hocus'],
  },
  options: {
    ...defaultConfig.options,
    prefix: 'tw-',
    important: true,
  },
}
```

Here are the steps that I took to create this file:

<ol class="spaced-y-6">
  <li>
    <p markdown="1">**Get the default configuration**. This is done using `require('tailwindcss/defaultConfig')()`. Essentially this has the same contents as the current `tailwind.js` file, though now it’s owned and maintained within Tailwind itself, and not by the user.</p>
    <p>Also any new or updated values within the default configuration will be automatically available.</p>
    <p markdown="1">This line is present but commented out in the current generated `tailwind.js` file.</p>
  </li>

  <li>
    <p markdown="1">**Create the colors object.** This will by default override Tailwind’s default colours, however you can add `...defaultConfig.colors` to include them and then add or edit values as needed.</p>
    <p markdown="1">In this example, I’m overridding the value used for the `black` colour classes to match the existing colour in the other CSS.</p>
  </li>

  <li>
    <p markdown="1">**Return the main configuration object.** For sites with no overrides, this could just be `module.exports = defaultConfig` for a minimal configuration.</p>
    <p markdown="1">To extend the defaults, add `...defaultConfig` at the beginning.</p>
  </li>

  <li>
    <p markdown="1">**Assign our colours.** Use them for `colors`, `textColors`, `backgroundColors` and `borderColours`.</p>
  </li>

  <li>
    <p markdown="1">**Add any plugins**. I use plugins on most projects, in this case I’m using [tailwindcss-interaction-variants](https://www.npmjs.com/package/tailwindcss-interaction-variants) and [tailwindcss-spaced-items](https://www.npmjs.com/package/tailwindcss-spaced-items). Usually the default `container` plugin would be here too.</p>
  </li>

  <li>
    <p markdown="1">**Add or override modules.** Here I’m adding the `hocus` (hover and focus) variant provided by the interaction variants plugin to the text style classes.</p>
  </li>

  <li>
    <p markdown="1">**Add or override options.** As this markup was originally from a Drupal website, I needed to override some of the options values. I’ve added the `tw-` prefix to avoid Tailwind classes from clashing with Drupal’s default markup, and set all Tailwind classes to use `!important` so that they override any existing styles.</p>
  </li>
</ol>

This file is only 27 lines long, so considerably shorter than the default file, and I think that it’s much easier to see what your additional and overridden values are, as well able to quickly recognise whether a class is generated from a custom value or from a Tailwind default value.

To move this file to the new format I think would be much easier as there’s no default configuration to filter out, and you can move across only what is needed.

## Other changes

### Consistent spacing for padding and margin

Similar to defining colours, you could also set some standard spacing values, and using those for padding, margin and negative margin to ensure that they are all consistent.

In this case, we can use `defaultConfig.margin` to get the default, add or override any values, and then assign it to the relevant sections of the main object.

```js
const spacing = {
  ...defaultConfig.margin,
  '2px': '2px',
}

module.exports = {
  ...defaultConfig,
  // ...
  padding: spacing,
  margin: spacing,
  negativeMargin: spacing,
  // ...
}
```

### Picking values with lodash

In the opposite to extending, if we wanted to limit the number of values within a part of the configuration, we can do that too. I’d suggest using the [pick method](https://lodash.com/docs/4.17.11#pick) provided by [Lodash](https://lodash.com).

From the documentation:

> Creates an object composed of the picked object properties.

For example, if we only wanted normal, medium and bold font weights:

```js
module.exports = {
  ...defaultConfig,
  // ...
  fontWeights: _.pick(defaultConfig.fontWeights, ['normal', 'medium', 'bold']),
  // ...
}
```

### Renaming the file

Also in Tailwind 1.0, it seems that the configuration file name is changing from `tailwind.js` to `tailwind.config.js`.

If you use [Laravel Mix](https://laravel-mix.com) and the [Laravel Mix Tailwind plugin](https://github.com/JeffreyWay/laravel-mix-tailwind) like I do on this site (even though it’s a Sculpin site), it will look for a `tailwind.js` file by default or you can specify whatever filename you need.

Here is an excerpt of the Tailwind configuration file for this site, using `tailwind.config.js`:

```js
mix.postCss('assets/css/app.css', 'source/dist/css')
  .tailwind('tailwind.config.js')
```

## Looking foward to Tailwind CSS 1.0!

Adam has said that Tailwind 1.0 should be released within a few weeks of the time of writing this, I assume once [the 1.0 To-Do list](https://github.com/tailwindcss/tailwindcss/issues/692) is completed.

I really like some of the improvements that are coming in 1.0, including the new configuration file format and the ability to easily add and extend values, as well as the file itself now being completely optional.

I can’t wait for it to land!
