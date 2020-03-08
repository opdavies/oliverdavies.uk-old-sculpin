---
title: Rebuilding Bartik (Drupal’s Default Theme) with Vue.js and Tailwind CSS - part 2
date: 2018-12-27
excerpt: A follow-up to my original post on rebuilding Bartik with Tailwind and Vue.js.
tags:
    - drupal
    - tailwind-css
    - tweet
    - vuejs
has_tweets: true
---
In [the original post](/blog/rebuilding-bartik-with-vuejs-tailwind-css) I detailed how I built [a clone of Drupal’s Bartik theme][netlify] with [Vue.js][vuejs] and [Tailwind CSS][tailwind]. This follow-up post details some updates that I’ve made to it since then.

## Customising Tailwind’s colours

During the first version of the page, my thoughts were to not edit the Tailwind configuration, however I changed my mind on this whilst working on the subsequent updates and did make some changes and customisations to the `tailwind.js` file.

By default, Tailwind includes a full colour palette including colours such as yellows, oranges, reds that weren’t being used in this page so they were removed. This makes the file more readable as well as reduces the number of classes that Tailwind generates.

Whist I was changing the colours, I also took the opportunity to tweak the values of the remaining colours to more closely match Bartik’s original colours.

I also added a `black-60` class which uses [RGBA](https://css-tricks.com/the-power-of-rgba) to provide a semi-transparent background. I used this when adding the [skip to main content link](#adding-the-skip-to-main-content-link).

```js
let colors = {
  'transparent': 'transparent',

  'black': '#22292f',
  'grey-darkest': '#3d4852',
  'grey-dark': '#8795a1',
  'grey': '#b8c2cc',
  'grey-light': '#dae1e7',
  'grey-lighter': '#f0f0f0',
  'grey-lightest': '#F6F6F2',
  'white': '#ffffff',

  'black-60': 'rgba(0, 0, 0, .6)',

  'blue-dark': '#2779bd',
  'blue': '#3490dc',
  'blue-light': '#bcdefa',

  'green-dark': '#325E1C',
  'green': '#77B159',
  'green-light': '#CDE2C2',
  'green-lighter': '#F3FAEE',
}
```

## Adding default styling for links

In the first version, every link was individually styled which resulted in a lot of duplicate classes and a potential maintenance issue.

I added a `style` section within `Welcome.vue`, and added some default styling for links based on their location on the page - [extracting some Tailwind components](https://tailwindcss.com/docs/extracting-components).

<div v-pre markdown="1">{% verbatim %}
```vuejs
<template>
  ...

  <div id="footer" class="text-xs text-white">
    <div class="container mx-auto px-4 pt-16 pb-4">
      <div class="border-t border-solid border-gray-900 pt-6 -mb-6">
        <div class="mb-6">
          <p><a href="#0">Contact</a></p>
        </div>

        <div class="mb-6">
          <p>
            A clone of <a href="https://www.drupal.org">Drupal</a>’s default theme (Bartik).
            Built by <a href="https://www.oliverdavies.uk">Oliver Davies</a>
            using <a href="https://vuejs.org">Vue.js</a>
            and <a href="https://tailwindcss.com">Tailwind CSS</a>.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
```

Within the `style` section, I’m able to use Tailwind’s custom `@apply` directive to inject it’s rules into more traditional CSS, rather than needing to add them onto every link.

```vuejs
<style lang="sass">
#header a
  @apply text-white no-underline

  &:hover,
  &:focus
    @apply underline

#main a
  @apply text-blue-dark no-underline border-b border-blue-600 border-dotted

  &:hover,
  &:focus
    @apply text-blue-600 border-solid

#footer a
  @apply text-white no-underline border-b border-dotted border-white

  &:hover,
  &:focus
    @apply border-none
</style>
```
{% endverbatim %}</div>

## Extracting a Vue component for Drupal blocks

As well as being able to extract re-usable components within Tailwind, the same can be done within Vue. As the page could potentially have multiple sidebar blocks, I extracted a `SidebarBlock` component which would act as a wrapper around the block’s contents.

```vuejs
// src/components/Sidebar.vue

<template>
  <div class="bg-gray-200 p-4 mb-4">
    <slot></slot>
  </div>
</template>
```

The component provides the wrapping div and the appropriate classes in a single easy-to-maintain location, and [uses a slot](https://vuejs.org/v2/guide/components-slots.html) as a placeholder for the main content.

That means that within `Welcome.vue`, the markup within the `sidebar-block` tags will be used as the block contents.

```html
<sidebar-block>
  <p>My block contents.</p>
</sidebar-block>
```

## Adding the Skip to Main Content Link

One thing [that was missing](https://github.com/opdavies/rebuilding-bartik/issues/1) was the 'Skip to main content link'. This an accessibility feature that allows for users who are navigating the page using only a keyboard to bypass the navigation links and skip straight to the main content if they wish by clicking a link that is hidden and only visible whilst it’s focussed on.

Here is the markup that I used, which is placed directly after the opening `<body>` tag.

```html
<a href="#0" class="skip-link text-white bg-black-60 py-1 px-2 rounded-b-lg focus:no-underline focus:outline-none">
  Skip to main content
</a>
```

I initially tried to implement the same feature on this website using [Tailwind’s visually hidden plugin](https://www.npmjs.com/package/tailwindcss-visuallyhidden) which also contains a `focussable` class, though I wasn’t able to style it the way that I needed. I created my own [Tailwind skip link plugin](https://www.npmjs.com/package/tailwindcss-skip-link) and moved the re-usable styling there.

To enable the plugin, I needed to add it within the `plugins` section of my `tailwind.js` file:

```js
plugins: [
  require('tailwindcss/plugins/container')(),
  require('tailwindcss-skip-link')(),
],
```

I added only the page-specific styling classes to the link (as well as the `skip-link` class that the plugin requires) as well as my own focus state to the skip link that I did within the `style` section of `App.vue`.

```vuejs
<style lang="sass">
@tailwind preflight
@tailwind components

.skip-link:focus
  left: 50%
  transform: translateX(-50%)

@tailwind utilities
</style>
```

![The Bartik clone with the skip to main content link visible](/images/blog/rebuilding-bartik-vue-tailwind-part-2/skip-link.png){.border}

## Adding the DrupalMessage component

I also added a version of Drupal’s status message as another Vue component. This also uses a slot to include the message contents and accepts a [prop](https://vuejs.org/v2/guide/components-props.html) for the message type.

```html
<template>
    <div :class="[ wrapperClasses, wrapperClasses ? 'pl-2 rounded-sm' : '' ]">
    <div class="py-4 pl-3 pr-4 mb-4 border flex items-center rounded-sm" :class="classes">
      <svg v-if="type == 'status'" class="fill-current w-4 h-4 text-green mr-3" xmlns="http://www.w3.org/2000/svg"><path d="M6.464 13.676a.502.502 0 0 1-.707 0L.797 8.721a.502.502 0 0 1 0-.707l1.405-1.407a.5.5 0 0 1 .707 0l2.849 2.848a.504.504 0 0 0 .707 0l6.629-6.626a.502.502 0 0 1 .707 0l1.404 1.404a.504.504 0 0 1 0 .707l-8.741 8.736z"/></svg>
      <slot></slot>
    </div>
  </div>
</template>
```

The value of the `type` prop is then used within some computed properties to determine the type specific classes to add (e.g. green for success, and red for warning), as well as whether or not to include the checkmark SVG image.

```js
<script>
export default {
  props: {
    type: String,
  },

  computed: {
    classes: function () {
      return {
        status: 'border-green-light text-green-dark bg-green-lighter',
      }[this.type]
    },

    wrapperClasses: function () {
      return {
        status: 'bg-green',
      }[this.type]
    },
  }
}
</script>
```
I did need to make one change to the `tailwind.js` file in order to change the border on links when they are hovered over - within `modules` I needed to enable the `borderStyle` module for hover and focus states in order for Tailwind to generate the additional classes.

```js
modules: {
  // ...
  borderStyle: ['responsive', 'hover', 'focus'],
  // ...
}
```

The message is included within the Welcome component by including the `<drupal-message />` element, though rather than importing it there, it’s registed as a global component so it would be available to any other components that could be added in the future.

This is done within `main.js`:

```js
// ...

Vue.component('drupal-message', require('@/components/DrupalMessage').default)

new Vue({
  render: h => h(App),
}).$mount('#app')
```

![The Bartik clone with the Drupal Message component visible](/images/blog/rebuilding-bartik-vue-tailwind-part-2/drupal-message.png){.border}

__The updated version is [live on Netlify][netlify], and the [latest source code is available on GitHub][github].__

[github]: https://github.com/opdavies/rebuilding-bartik
[netlify]: https://rebuilding-bartik.oliverdavies.uk
[tailwind]: https://tailwindcss.com
[vuejs]: https://vuejs.org
