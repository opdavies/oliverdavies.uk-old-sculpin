---
title: Building Gmail Filters with PHP
tags:
    - php
    - gmail
use: [posts]
redirect:
    - blog/building-gmail-filters-in-php/
---
Earlier this week I wrote a small PHP library called [GmailFilterBuilder][0] that allows you to write Gmail filters in PHP and export them to XML.

I was already aware of a Ruby library called [gmail-britta][1] that does the same thing, but a) I’m not that familiar with Ruby so the syntax wasn’t that natural to me - it’s been a while since I wrote any Puppet manifests, and b) it seemed like a interesting little project to work on one evening.

The library contains two classes - `GmailFilter` which is used to create each filter, and `GmailFilterBuilder` that parses the filters and generates the XML using a [Twig][2] template.

## Usage

For example:

```php
# test.php

require __DIR__ '/vendor/autoload.php';

use Opdavies\GmailFilterBuilder\Builder;
use Opdavies\GmailFilterBuilder\Filter;

$filters = [];

$filters[] = Filter::create()
    ->has('from:example@test.com')
    ->labelAndArchive('Test')
    ->neverSpam();

new Builder($filters);
```

In this case, an email from `example@test.com` would be archived, never marked as spam, and have a label of "Test" added to it.

With this code written, and the GmailFilterBuilder library installed via Composer, I can run `php test.php` and have the XML written to the screen.

This can also be written to a file - `php test.php > filters.xml` - which can then be imported into Gmail.

## Twig Extensions

I also added a custom Twig extension that I moved into a separate [twig-extensions][5] library so that I and other people can re-use it in other projects.

It’s a simple filter that accepts a boolean and returns `true` or `false` as a string, but meant that I could remove three ternary operators from the template and replace them with the `boolean_string` filter.

Before:

    {% raw %}{{ filter.isArchive ? 'true' : 'false' }}{% endraw %}

After:

    {% raw %}{{ filter.isArchive|boolean_string }}{% endraw %}

This can then be used to generate output like this, whereas having blank values would have resulted in errors when importing to Gmail.

    <apps:property name='shouldArchive' value='true'/>

## Example

For a working example, see my personal [gmail-filters][3] repository on GitHub.

## Resources

* [The GmailFilterBuilder library on Packagist][4]
* [My Gmail filters on GitHub][3]
* [My Twig Extensions on Packagist][5]

[0]: https://github.com/opdavies/gmail-filter-builder
[1]: https://github.com/antifuchs/gmail-britta
[2]: http://twig.sensiolabs.org
[3]: https://github.com/opdavies/gmail-filters
[4]: https://packagist.org/packages/opdavies/gmail-filter-builder
[5]: https://packagist.org/packages/opdavies/twig-extensions
