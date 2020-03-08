---
title: Building the new PHPSW Website
date: 2018-02-28
excerpt: Earlier this week we had another hack night, working on the new PHPSW user group website.
tags:
    - phpsw
    - symfony
    - tailwind-css
has_tweets: true
---
Earlier this week we had another hack night, working on the new [PHPSW user group][0] website.

<div class="mb-4">
    <blockquote class="twitter-tweet" data-lang="en"><p lang="en" dir="ltr">Hacking away on the new <a href="https://twitter.com/phpsw?ref_src=twsrc%5Etfw">@phpsw</a> website with <a href="https://twitter.com/DaveLiddament?ref_src=twsrc%5Etfw">@DaveLiddament</a> and <a href="https://twitter.com/kasiazien?ref_src=twsrc%5Etfw">@kasiazien</a>. <a href="https://t.co/kmfjdQSOUq">pic.twitter.com/kmfjdQSOUq</a></p>&mdash; Oliver Davies (@opdavies) <a href="https://twitter.com/opdavies/status/968224364129906688?ref_src=twsrc%5Etfw">February 26, 2018</a></blockquote>
</div>

It’s built with Symfony so it’s naturally using Twig for templating. I’ve become a big fan of the utility based approach to CSS and [Tailwind CSS][1] in particular, so I’m using that for all of the styling, and using [Webpack Encore][2] to compile all of the assets.

We have an integration with Meetup.com which we’re using to pull all of our previous event data and store them as JSON files for Symfony to parse and render, which it then uses to generate static HTML to upload onto the server.

We’re in the process of populating all of the past data, but look out for a v1 launch soon. In the meantime, feel free to take a peek at our [GitHub repository][3].

[0]: https://phpsw.uk
[1]: https://tailwindcss.com
[2]: https://github.com/symfony/webpack-encore
[3]: https://github.com/phpsw/phpsw-ng
