---
title: Easier Sculpin Commands with Composer and NPM Scripts
date: 2017-01-07
excerpt: In this video, I show you how I've simplied my Sculpin and Gulp workflow using custom Composer and NPM scripts.
tags: [composer, gulp, sculpin]
---
In this video, I show you how I've simplied my Sculpin and Gulp workflow using custom Composer and NPM scripts.

My website includes several various command line tools - e.g. [Sculpin][4], [Gulp][5] and [Behat][6] - each needing different arguments and options, depending on the command being run. For example, for Sculpin, I normally include several additional options when viewing the site locally - the full command that I use is `./vendor/bin/sculpin generate --watch --server --clean --no-interaction`. Typing this repeatedly is time consuming and could be easily mis-typed, forgotten or confused with other commands.

In this video, I show you how I've simplied my Sculpin and Gulp workflow using custom Composer and NPM scripts.

<div class="embed-container">
    <iframe width="560" height="315" src="https://www.youtube.com/embed/eiWDV_63yCQ" frameborder="0" allowfullscreen></iframe>
</div>

## Scripts

Here are the scripts that I’m using - they are slightly different from those in the video. I use the `--generate` and `--watch` options for Sculpin and the `gulp watch` command for NPM. I had to change these before the recording as I was using the [demo magic][0] script to run the commands, and existing from a watch session was also ending the script process.

### composer.json

```language-json
"scripts": {
    "clean": "rm -rf output_*/",
    "dev": "sculpin generate --clean --no-interaction --server --watch",
    "production": "sculpin generate --clean --no-interaction --env='prod' --quiet"
}
```

Run with `composer run <name>`, e.g. `composer run dev`.

### package.json

```language-json
"scripts": {
    "init": "yarn && bower install",
    "dev": "gulp watch",
    "production": "gulp --production"
}
```

Run with `npm run <name>`, e.g. `npm run production`.

You can also take a look at the full [composer.json][1] and [package.json][2] files within my site repository on [GitHub][3].

## Resources

- [Composer scripts][7]
- [oliverdavies.uk composer.json][1]
- [oliverdavies.uk package.json][2]

[0]: https://github.com/paxtonhare/demo-magic
[1]: https://github.com/opdavies/oliverdavies.uk/blob/master/composer.json
[2]: https://github.com/opdavies/oliverdavies.uk/blob/master/package.json
[3]: https://github.com/opdavies/oliverdavies.uk
[4]: https://sculpin.io
[5]: http://gulpjs.com
[6]: http://behat.org
[7]: https://getcomposer.org/doc/04-schema.md#scripts
