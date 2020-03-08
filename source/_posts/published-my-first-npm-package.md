---
title: Published my first NPM package
date: 2018-12-16
excerpt: Yesterday I published my first module onto NPM, and it’s a plugin for Tailwind CSS to be used alongside Vue.js.
tags:
    - npm
    - tailwind-css
    - vuejs
---
Yesterday I published my first module onto NPM, and it’s a plugin for [Tailwind CSS][tailwind] to be used alongside [Vue.js](https://vuejs.org).

The plugin adds classes for showing and hiding elements in different display variations in combination with Vue's [v-cloak directive](https://vuejs.org/v2/api/#v-cloak), which I originally saw in [the first 'Building Kitetail' video](https://youtu.be/XUXpcbYQ_iQ?t=2360). These are useful for when you want an element to be visible whilst Vue is compiling, and hidden afterwards.

Here is the compiled CSS that is added by the plugin:

```css
[v-cloak] .v-cloak-block {
  display: block;
}

[v-cloak] .v-cloak-flex {
  display: flex;
}

[v-cloak] .v-cloak-hidden {
  display: none;
}

[v-cloak] .v-cloak-inline {
  display: inline;
}

[v-cloak] .v-cloak-inline-block {
  display: inline-block;
}

[v-cloak] .v-cloak-inline-flex {
  display: inline-flex;
}

[v-cloak] .v-cloak-invisible {
  visibility: hidden;
}

.v-cloak-block,
.v-cloak-flex,
.v-cloak-inline,
.v-cloak-inline-block,
.v-cloak-inline-flex {
  display: none;
}
```

The `v-cloak` directive exists on an element until Vue finishes compiling, after which it is removed. Therefore adding a `v-cloak-block` class to an element will make it `display: block` whilst Vue is compiling and the element is cloaked, and `display: none` afterwards when the Vue markup is compiled and rendered.

In my `base.html.twig` template, I’ve added `v-cloak` to the wrapper div within the `body`.

{% verbatim %}<div v-pre markdown="1">
```twig
<body class="font-sans leading-normal">
    <div id="app" v-cloak>
        {# ... #}
    </div>
</body>
```
</div>{% endverbatim %}

Within my `navbar.html.twig` partial, I have a placeholder div that also contains the site name, which is instantly visible but has the `v-cloak-block` class so it’s hidden once Vue has compiled and the `Navbar` Vue component is visible instead.

{% verbatim %}<div v-pre markdown="1">
```twig
<div class="border-bottom border-b border-gray-300 mb-6">
    <div class="container mx-auto">
        <div class="block py-5 v-cloak-block">
            {{ site.title }}
        </div>

        <navbar
            site-name="{{ site.title }}"
            page-url="{{ page.url }}"
        ></navbar>
    </div>
</div>
```
</div>{% endverbatim %}

I was originally surprised that these classes weren’t included as part of Tailwind or as part of an existing plugin, but as I’ve already used these styles on several projects that include Vue.js with Symfony or Sculpin, it made sense to extract it into a plugin and make it available as a npm package which I can easily add to any project - as well as making it easier to maintain if I need to add additional variations at a later point.

**You can view [the package on npmjs.com][npm], and [the code repository on GitHub][github].**

[github]: https://github.com/opdavies/tailwindcss-vuejs
[npm]: https://www.npmjs.com/package/tailwindcss-vuejs
[tailwind]: https://tailwindcss.com
