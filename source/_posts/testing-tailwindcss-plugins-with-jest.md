---
title: Testing Tailwind CSS plugins with Jest
date: 2019-04-29
excerpt: How to write tests for Tailwind CSS plugins using Jest.
tags:
    - javascript
    - jest
    - tailwind-css
    - testing
promoted: true
---
<div class="note" markdown="1">
**Note:** The content of this post is based on tests seen in Adam Wathan’s ["Working on Tailwind 1.0" video][working-on-tailwind-video], the Jest documentation website, and existing tests for other Tailwind plugins that I’ve used such as [Tailwind CSS Interaction Variants][tailwindcss-interaction-variants].
</div>

## Preface

In Tailwind 0.x, there was a `list-reset` utility that reset the list style and padding on a HTML list, though it was removed prior to 1.0 and moved into Tailwind’s base styles and applied by default.

However, on a few projects I use Tailwind in addition to either existing custom styling or another CSS framework, and don’t use `@tailwind base` (formerly `@tailwind preflight`) so don’t get the base styles.

Whilst I could re-create this by replacing it with two other classes (`list-none` and `p-0`), I decided to write [my own Tailwind CSS plugin][repo] to re-add the `list-reset` class. This way I could keep backwards compatibility in my projects and only need to add one class in other future instances.

In this post, I’ll use this as an example to show how to write tests for Tailwind CSS plugins with a JavaScript testing framework called [Jest][jest].

More information about plugins for Tailwind CSS themselves can be found on the [Tailwind website][tailwind-docs-plugins].

## Add dependencies

To start, we need to include `jest` as a dependency of the plugin, as well as `jest-matcher-css` to perform assertions against the CSS that the plugin generates.

We also need to add `tailwindcss` and `postcss` so that we can use them within the tests.

```plain
yarn add -D jest jest-matcher-css postcss tailwindcss@next
```

This could be done with `yarn add` or `npm install`.

## Writing the first test

In this plugin, the tests are going to be added into a new file called `test.js`. This file is automatically loaded by Jest based on it’s [testRegex setting][jest-testregex-setting].

This is the format for writing test methods:

```js
test('a description of the test', () => {
  // Perform tasks and write assertions
})
```

The first test is to ensure that the correct CSS is generated from the plugin using no options.

We do this by generating the plugin’s CSS, and asserting that it matches the expected CSS within the test.

```js
test('it generates the list reset class', () => {
  generatePluginCss().then(css => {
    expect(css).toMatchCss(`
      .list-reset {
        list-style: none;
        padding: 0
      }
    `)
  })
})
```

However, there are some additional steps needed to get this working.

### Generating the plugin’s CSS

Firstly, we need to import the plugin’s main `index.js` file, as well as PostCSS and Tailwind. This is done at the beginning of the `test.js` file.

```js
const plugin = require('./index.js')
const postcss = require('postcss')
const tailwindcss = require('tailwindcss')
```

Now we need a way to generate the CSS so assertions can be written against it.

In this case, I’ve created a function called `generatePluginCss` that accepts some optional options, processes PostCSS and Tailwind, and returns the CSS.

```js
const generatePluginCss = (options = {}) => {
  return postcss(
    tailwindcss()
  )
  .process('@tailwind utilities;', {
    from: undefined
  })
  .then(result => result.css)
}
```

Alternatively, to test the output of a component, `@tailwind utilities;` would be replaced with `@tailwind components`.

```js
.process('@tailwind components;', {
  from: undefined
})
```

Whilst `from: undefined` isn’t required, if it’s not included you will get this message:

> Without `from` option PostCSS could generate wrong source map and will not find Browserslist config. Set it to CSS file path or to `undefined` to prevent this warning.

### Configuring Tailwind

In order for the plugin to generate CSS, it needs to be enabled within the test, and Tailwind’s core plugins need to be disabled so that we can assert against just the output from the plugin.

As of Tailwind 1.0.0-beta5, this can be done as follows:

```
tailwindcss({
  corePlugins: false,
  plugins: [plugin(options)]
})
```

In prior versions, each plugin in `corePlugins` needed to be set to `false` separately.

I did that using a `disableCorePlugins()` function and [lodash][lodash], using the keys from `variants`:

```
const _ = require('lodash')

// ...

const disableCorePlugins = () => {
  return _.mapValues(defaultConfig.variants, () => false)
}
```

### Enabling CSS matching

In order to compare the generated and expected CSS, [the CSS matcher for Jest][jest-css-matcher] needs to be required and added using [expect.extend][jest-expect-extend].

```js
const cssMatcher = require('jest-matcher-css')

...

expect.extend({
  toMatchCss: cssMatcher
})
```

Without it, you’ll get an error message like _"TypeError: expect(...).toMatchCss is not a function"_ when running the tests.

## The next test: testing variants

To test variants we can specify the required variant names within as options to `generatePluginCss`.

For example, this is how to enable `hover` and `focus` variants.

```js
generatePluginCss({ variants: ['hover', 'focus'] })
```

Now we can add another test that generates the variant classes too, to ensure that also works as expected.

```js
test('it generates the list reset class with variants', () => {
  generatePluginCss({ variants: ['hover', 'focus'] }).then(css => {
    expect(css).toMatchCss(`
      .list-reset {
        list-style: none;
        padding: 0
      }

      .hover\\:list-reset:hover {
        list-style: none;
        padding: 0
      }

      .focus\\:list-reset:focus {
        list-style: none;
        padding: 0
      }
    `)
  })
})
```

## Running tests locally

Now that we have tests, we need to be able to run them.

With Jest included as a dependency, we can update the `test` script within `package.json` to execute it rather than returning a stub message.

```diff
- "test": "echo \"Error: no test specified\" && exit 1"
+ "test": "jest"
```

This means that as well as running the `jest` command directly to run the tests, we can also run `npm test` or `yarn test`.

After running the tests, Jest will display a summary of the results:

![A screenshot of the Jest output after running the tests, showing 1 passed test suite and 2 passed tests, as well as the test run time.](/images/blog/testing-tailwindcss-plugins/running-tests.png)

## Running tests automatically with Travis CI

As well as running the tests locally, they can also be run automatically via services like [Travis CI][travis] when a new pull request is submitted or each time new commits are pushed.

This is done by adding a  `.travis-ci.yml` file to the repository, like this one which is based on the [JavaScript and Node.js example][travis-nodejs-example]:

```yml
language: node_js

node_js:
  - '8'

cache:
  directories:
    - node_modules

before_install:
  - npm update

install:
  - npm install

script:
  - npm test
```

With this in place, the project can now be enabled on the Travis website, and the tests will be run automatically.

For this plugin, you can see the results at <https://travis-ci.org/opdavies/tailwindcss-list-reset>.

[jest-css-matcher]: https://www.npmjs.com/package/jest-matcher-css
[jest-expect-extend]: https://jestjs.io/docs/en/expect#expectextendmatchers
[jest-testregex-setting]: https://jestjs.io/docs/en/configuration#testregex-string-array-string
[jest]: https://jestjs.io
[lodash]: https://lodash.com
[repo]: https://github.com/opdavies/tailwindcss-list-reset
[tailwind-docs-plugins]: https://tailwindcss.com/docs/plugins
[tailwindcss-interaction-variants]: https://www.npmjs.com/package/tailwindcss-interaction-variants
[travis-nodejs-example]: https://docs.travis-ci.com/user/languages/javascript-with-nodejs
[travis]: https://travis-ci.org
[working-on-tailwind-video]: https://www.youtube.com/watch?v=SkTKN38wSEM
