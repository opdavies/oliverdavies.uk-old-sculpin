---
title: Examples of using Laravel Collections in Drupal
date: 2018-08-23
excerpt: Some examples of using Laravel’s Illuminate Collections within Drupal projects.
tags:
    - drupal
    - drupal-7
    - drupal-8
    - drupal-planet
    - laravel
    - laravel-collections
    - php
has_tweets: true
---
Since starting to work with Laravel as well as Drupal and Symfony, watching Adam Wathan’s [Refactoring to Collections][0] course as well as [lessons on Laracasts][6], I’ve become a fan of [Laravel’s Illuminate Collections][1] and the object-orientated pipeline approach for interacting with PHP arrays.

In fact I’ve given a talk on [using Collections outside Laravel][2] and have written a [Collection class module][3] for Drupal 7.

I’ve also tweeted several examples of code that I’ve written within Drupal that use Collections, and I thought it would be good to collate them all here for reference.

Thanks again to [Tighten][4] for releasing and maintaining the [tightenco/collect library][5] that makes it possible to pull in Collections via Composer.

<div class="lg:flex lg:flex-wrap lg:-mx-4">
    {% include 'tweet' with {
        class: 'block mb-4 lg:w-1/2 lg:px-2 lg:mb-0',
        data_cards: true,
        content: '<p lang="en" dir="ltr">Putting <a href="https://twitter.com/laravelphp?ref_src=twsrc%5Etfw">@laravelphp</a>&#39;s Collection class to good use, cleaning up some of my <a href="https://twitter.com/drupal?ref_src=twsrc%5Etfw">@drupal</a> 8 code. Thanks <a href="https://twitter.com/TightenCo?ref_src=twsrc%5Etfw">@TightenCo</a> for the Collect library! <a href="https://t.co/Bn1UfudGvp">pic.twitter.com/Bn1UfudGvp</a></p>&mdash; Oliver Davies (@opdavies) <a href="https://twitter.com/opdavies/status/898577157193998337?ref_src=twsrc%5Etfw">August 18, 2017</a>',
    } %}

    {% include 'tweet' with {
        class: 'block mb-4 lg:w-1/2 lg:px-2 lg:mb-0',
        data_cards: true,
        content: '<p lang="en" dir="ltr">Putting more <a href="https://twitter.com/laravelphp?ref_src=twsrc%5Etfw">@laravelphp</a> Collections to work in my <a href="https://twitter.com/drupal?ref_src=twsrc%5Etfw">@drupal</a> code today. 😁 <a href="https://t.co/H8xDTT063X">pic.twitter.com/H8xDTT063X</a></p>&mdash; Oliver Davies (@opdavies) <a href="https://twitter.com/opdavies/status/963890078933282817?ref_src=twsrc%5Etfw">February 14, 2018</a>',
    } %}

    {% include 'tweet' with {
        class: 'block mb-4 lg:w-1/2 lg:px-2 lg:mb-0',
        data_cards: true,
        content: '<p lang="en" dir="ltr">I knew that you could specify a property like &#39;price&#39; in Twig and it would also look for methods like &#39;getPrice()&#39;, but I didn&#39;t know (or had maybe forgotten) that <a href="https://twitter.com/laravelphp?ref_src=twsrc%5Etfw">@laravelphp</a> Collections does it too.<br><br>This means that these two Collections return the same result.<br><br>Nice! 😎 <a href="https://t.co/2g2IfThzdy">pic.twitter.com/2g2IfThzdy</a></p>&mdash; Oliver Davies (@opdavies) <a href="https://twitter.com/opdavies/status/1009451206765416448?ref_src=twsrc%5Etfw">June 20, 2018</a>',
    } %}

    {% include 'tweet' with {
        class: 'block mb-4 lg:w-1/2 lg:px-2 lg:mb-0',
        data_cards: true,
        content: '<p lang="en" dir="ltr">More <a href="https://twitter.com/laravelphp?ref_src=twsrc%5Etfw">@laravelphp</a> Collection goodness, within my <a href="https://twitter.com/hashtag/Drupal8?src=hash&amp;ref_src=twsrc%5Etfw">#Drupal8</a> project! <a href="https://t.co/mWgpNbNIrh">pic.twitter.com/mWgpNbNIrh</a></p>&mdash; Oliver Davies (@opdavies) <a href="https://twitter.com/opdavies/status/1027843931101380608?ref_src=twsrc%5Etfw">August 10, 2018</a>',
    } %}

    {% include 'tweet' with {
        class: 'block mb-4 lg:w-1/2 lg:px-2 lg:mb-0',
        data_cards: true,
        content: '<p lang="en" dir="ltr">Some more <a href="https://twitter.com/hashtag/Drupal?src=hash&amp;ref_src=twsrc%5Etfw">#Drupal</a> 8 fun with Laravel Collections. Loading the tags for a post and generating a formatted string of tweetable hashtags. <a href="https://t.co/GbyiRPzIRo">pic.twitter.com/GbyiRPzIRo</a></p>&mdash; Oliver Davies (@opdavies) <a href="https://twitter.com/opdavies/status/1032544228029673472?ref_src=twsrc%5Etfw">August 23, 2018</a>',
    } %}
</div>

[0]: https://adamwathan.me/refactoring-to-collections
[1]: https://laravel.com/docs/collections
[2]: /talks/using-laravel-collections-outside-laravel
[3]: https://www.drupal.org/project/collection_class
[4]: https://tighten.co
[5]: https://packagist.org/packages/tightenco/collect
[6]: https://laracasts.com/series/how-do-i/episodes/18
