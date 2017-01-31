---
title: How I Fixed the Line Numbers in my Source Maps
tags: ['sass', 'gulp']
draft: true
---
I use [Gulp] and [Sass] in most of my projects for writing and generating CSS, as well as other front-end assets. In development, as well as the compiled `.css` files, Gulp also generates a `.css.map` file that maps the generated code with the original so that browsers can display the correct information.

With no sourcemap, the browser can only link to the compiled CSS:

![](/assets/images/blog/no-sourcemap.png)

With a sourcemap, the browser can tell exactly where the rule was added. In this case, it's line 106 within Bootstrap’s `_scaffolding.scss` file.

![](/assets/images/blog/with-sourcemap.png)

However, I noticed an instance where the wrong line number was being displayed, and in some cases, the wrong file name.

This was the layout of my `sass` directory:

```bash
.
├── _base.sass
├── _mixins.sass
├── base
│   └── _code.sass
├── components
│   ├── _availability.sass
│   ├── _badges.sass
│   ├── _footer.sass
│   ├── _meetups.sass
│   ├── _testimonials.sass
│   └── content-types
│       └── _blog-post.sass
├── pages
│   ├── _about.sass
│   ├── _blog-list.sass
│   ├── _blog-post.sass
│   ├── _experience.sass
│   └── _projects.sass
└── site.sass

4 directories, 18 files
```

This is how my `site.sass` file looked:

```sass
@import '../../vendor/bower_components/bootstrap-sass/assets/stylesheets/bootstrap'
@import '../../vendor/bower_components/compass-breakpoint/stylesheets/breakpoint'

@import 'base'
@import 'mixins'
@import 'components/**/*'
@import 'pages/**/*'
```
After some debugging, the `components/**/*` and `pages/**/*` lines were the ones that proved to be problematic. These lines import all of the partials from the `components` and `pages` directories, as well as any sub-directories.
