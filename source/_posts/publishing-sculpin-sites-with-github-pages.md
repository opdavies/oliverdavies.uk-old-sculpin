---
title: Publishing Sculpin Sites with GitHub Pages
date: 2017-07-13
excerpt: How I moved my website to GitHub pages.
tags: [sculpin, php, github]
meta:
    image:
        url: '/images/blog/jackson-octocat.png'
        type: 'image/png'
        height: 200
        width: 451
---
<p class="text-center" markdown="1">![](/images/blog/jackson-octocat.png)</p>

Earlier this week I moved this site from my personal Linode server to [GitHub Pages][0].

This made sense as I already kept the source code in [on GitHub][1], the issue was that GitHub Pages doesn’t know how to dynamically parse and generate a Sculpin site like it does with some other static site generators. It can though parse and serve HTML files, which is what Sculpin generates. It’s just a case of how those files are added to GitHub.

I’ve seen different implementations of this, mostly where the Sculpin code is on one branch, and the generated HTML code is on a separate `gh-pages` or `master` branch (depending on your repository name). I’m not fond of this approach as it means automatically checking out and merging branches which can get messy, and also it’s weird to look at a repo’s branches page and see one branch maybe tens or hundreds of commits both ahead and behind the default branch.

This has been made simpler and tidier now that we can use a `docs` directory within the repository to serve content.

<img
    src="/images/blog/github-pages.png"
    alt=""
    class="is-centered"
    style="margin-top: 20px; margin-bottom: 20px"
>

This means that I can simply re-generate the site after making changes and add it as an additional commit to my main branch with no need to switch branches or perform a merge.

To simplify this, I’ve added a new [publish.sh script][3] into my repository to automate the sites. This is how it currently looks:

```language-bash
#!/usr/bin/env bash

SITE_ENV="prod"

# Remove the existing docs directory, build the site and create the new
# docs directory.
rm -rf ./docs
vendor/bin/sculpin generate --no-interaction --clean --env=${SITE_ENV}
touch output_${SITE_ENV}/.nojekyll
mv output_${SITE_ENV} docs

# Ensure the correct Git variables are used.
git config --local user.name 'Oliver Davies'
git config --local user.email oliver@oliverdavies.uk

# Add, commit and push the changes.
git add --all docs
git commit -m 'Build.'
git push origin HEAD
```

This begins by removing the deleting the existing `docs` directory and re-generating the site with the specified environment. Then I add a `.nojekyll` file and rename the output directory to replace `docs`.

Now the changes can be added, committed and pushed. Once pushed, the new code is automatically served by GitHub Pages.

## HTTPS

GitHub Pages unfortunately does [not support HTTPS for custom domains][7].

As the site was previously using HTTPS, I didn’t want to have to go back to HTTP, break any incoming links and lose any potential traffic. To continue using HTTPS, I decided to [use Cloudflare][6] to serve the site via their CDN which does allow for HTTPS traffic.

## Next Steps

- Enable automatically running `publish.sh` when new changes are pushed to GitHub rather than running it manually. I was previously [using Jenkins][4] and Fabric for this, though I’m also going to look into using Travis to accomplish this.
- Add the pre-build steps such as running `composer install` and `yarn` to install dependencies, and `gulp` to create the front-end assets. This was previously done by Jenkins in my previous setup.

## Resources

- [Publishing your GitHub Pages site from a /docs folder on your master branch][2]
- [Bypassing Jekyll on GitHub Pages][5]
- [Secure and fast GitHub Pages with CloudFlare][6]

[0]: https://pages.github.com
[1]: https://github.com/opdavies/oliverdavies.uk
[2]: https://help.github.com/articles/configuring-a-publishing-source-for-github-pages/#publishing-your-github-pages-site-from-a-docs-folder-on-your-master-branch
[3]: https://github.com/opdavies/oliverdavies.uk/blob/master/publish.sh
[4]: /articles/2015/07/21/automating-sculpin-jenkins
[5]: https://github.com/blog/572-bypassing-jekyll-on-github-pages
[6]: https://blog.cloudflare.com/secure-and-fast-github-pages-with-cloudflare
[7]: https://github.com/blog/2186-https-for-github-pages
