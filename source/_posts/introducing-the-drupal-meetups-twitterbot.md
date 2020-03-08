---
title: Introducing the Drupal Meetups Twitterbot
date: 2017-06-09
excerpt: I’ve written a twitterbot for promoting Drupal meetups.
tags:
    - twitter
    - php
---
<p class="text-center" markdown="1">![](/images/blog/drupal-meetups-twitterbot.png)</p>

The [Drupal Meetups Twitterbot][0] is a small project that I worked on a few months ago, but hadn't got around to promoting yet. It’s intention is to provide [one Twitter account][1] where people can get the up to date news from various Drupal meetups.

It works by having a whitelist of [Twitter accounts and hashtags][2] to search for, uses [Codebird][3] to query the Twitter API and retweets any matching tweets on a scheduled basis.

If you would like your meetup group to be added to the list of searched accounts, please [open an issue][4] on the GitHub repo.

[0]: https://github.com/opdavies/drupal-meetups-twitterbot
[1]: https://twitter.com/drupal_meetups
[2]: https://github.com/opdavies/drupal-meetups-twitterbot/blob/master/bootstrap/config.php
[3]: https://www.jublo.net/projects/codebird/php
[4]: https://github.com/opdavies/drupal-meetups-twitterbot/issues/new
