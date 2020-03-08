---
title: Include CSS Fonts by Using a SASS each Loop
date: 2014-11-18
excerpt: How to use an SASS each loop to easily add multiple fonts to your CSS.
tags:
  - compass
  - drupal-planet
  - fonts
  - sass
---
How to use an @each loop in SASS to quickly include multiple font files within your stylesheet.

Using a file structure similar to this, organise your font files into directories, using the the font name for both the directory name and for the file names.

```language-bash
.
├── FuturaBold
│   ├── FuturaBold.eot
│   ├── FuturaBold.svg
│   ├── FuturaBold.ttf
│   └── FuturaBold.woff
├── FuturaBoldItalic
│   ├── FuturaBoldItalic.eot
│   ├── FuturaBoldItalic.svg
│   ├── FuturaBoldItalic.ttf
│   └── FuturaBoldItalic.woff
├── FuturaBook
│   ├── FuturaBook.eot
│   ├── FuturaBook.svg
│   ├── FuturaBook.ttf
│   └── FuturaBook.woff
├── FuturaItalic
│   ├── FuturaItalic.eot
│   ├── FuturaItalic.svg
│   ├── FuturaItalic.ttf
│   └── FuturaItalic.woff
```

Within your SASS file, start an `@each` loop, listing the names of the fonts. In the same way as PHP's `foreach` loop, each font name will get looped through using the `$family` variable and then compiled into CSS.

```language-scss
@each $family in FuturaBook, FuturaBold, FuturaBoldItalic, FuturaItalic {
  @font-face {
    font-family: #{$family};
    src: url('../fonts/#{$family}/#{$family}.eot');
    src: url('../fonts/#{$family}/#{$family}.eot?#iefix') format('embedded-opentype'),
         url('../fonts/#{$family}/#{$family}.woff') format('woff'),
         url('../fonts/#{$family}/#{$family}.ttf') format('truetype'),
         url('../fonts/#{$family}/#{$family}.svg##{$family}') format('svg');
    font-weight: normal;
    font-style: normal;
  }
}
```

When the CSS has been compiled, you can then use in your CSS in the standard way.

```language-scss
font-family: "FuturaBook";
```
