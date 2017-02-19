---
title: Automating Sculpin Builds with Jenkins CI
date: 2015-07-21
tags:
    - sculpin
    - jenkins
slug: automating-sculpin-jenkins
use: [posts]
redirect:
    - blog/automating-sculpin-jenkins/
---
As part of re-building this site with [Sculpin](http://sculpin.io), I wanted to automate the deployments, as in I wouldn't need to run a script like [publish.sh](https://raw.githubusercontent.com/sculpin/sculpin-blog-skeleton/master/publish.sh) locally and have that deploy my code onto my server. Not only did that mean that my local workflow was simpler (update, commit and push, rather than update, commit, push and deploy), but if I wanted to make a quick edit or hotfix, I could log into GitHub or Bitbucket (wherever I decided to host the source code) from any computer or my phone, make the change and have it deployed for me.

I'd started using [Jenkins CI](http://jenkins-ci.org) during my time at the Drupal Association, and had since built my own Jenkins server to handle deployments of Drupal websites, so that was the logical choice to use.

## Installing Jenkins and Sculpin

If you don’t already have Jenkins installed and configured, I'd suggest using [Jeff Geerling](http://jeffgeerling.com/) (aka geerlingguy)'s [Ansible role for Jenkins CI](https://galaxy.ansible.com/list#/roles/440).

I've also released an [Ansible role for Sculpin](https://galaxy.ansible.com/list#/roles/4063) that installs the executable so that the Jenkins server can run Sculpin commands.

## Triggering a Build from a Git Commit

I created a new Jenkins item for this task, and restricted where it could be run to `master` (i.e. the Jenkins server rather than any of the nodes).

### Polling from Git

I entered the url to the [GitHub repo](https://github.com/opdavies/oliverdavies.uk) into the **Source Code Management** section (the Git option *may* have been added by the [Git plugin](https://wiki.jenkins-ci.org/display/JENKINS/Git+Plugin) that I have installed).

As we don’t need any write access back to the repo, using the HTTP URL rather than the SSH one was fine, and I didn’t need to provide any additional credentials.

Also, as I knew that I’d be working a lot with feature branches, I entered `*/master` as the only branch to build. This meant that pushing changes or making edits on any other branches would not trigger a build.

![Defining the Git repository in Jenkins](/assets/images/blog/oliverdavies-uk-jenkins-git-repo.png)

I also checked the **Poll SCM** option so that Jenkins would be routinely checking for updated code. This essentially uses the same syntax as cron, specifying minutes, hours etc. I entered `* * * * *` so that Jenkins would poll each minute, knowing that I could make this less frequent if needed.

This now that Jenkins would be checking for any updates to the repo each minute, and could execute tasks if needed.

### Building and Deploying

Within the **Builds** section of the item, I added an *Execute Shell* step, where I could enter a command to execute. Here, I pasted a modified version of the original publish.sh script.

    #!/bin/bash

    set -uex

    sculpin generate --env=prod --quiet
    if [ $? -ne 0 ]; then echo "Could not generate the site"; exit 1; fi

    rsync -avze 'ssh' --delete output_prod/ prodwww2:/var/www/html/oliverdavies.uk/htdocs
    if [ $? -ne 0 ]; then echo "Could not publish the site"; exit 1; fi

This essentially is the same as the original file, in that Sculpin generates the site, and uses rsync to deploy it somewhere else. In my case, `prodwww2` is a Jenkins node (this alias is configured in `/var/lib/jenkins/.ssh/config`), and `/var/www/html/oliverdavies.uk/htdocs` is the directory from where my site is served.

## Building Periodically

There is some dynamic content on my site, specifically on the Talks page. Each talk has a date assigned to it, and within the Twig template, the talk is positoned within upcoming or previous talks based on whether this date is less or greater than the time of the build.

The YAML front matter:

    ---
    ...
    talks:
        - title: Test Drive Twig with Sculpin
          date: 2015-07-24
          location: DrupalCamp North
    ---

The Twig layout:

    {% raw -%}
    {% for talk in talks|reverse if talk.date >= now %}
        {# Upcoming talks #}
    {% endfor %}

    {% for talk in talks if talk.date < now %}
        {# Previous talks #}
    {% endfor%}
    {%- endraw %}

I also didn’t want to have to push an empty commit or manually trigger a job in Jenkins after doing a talk in order for it to be positioned in the correct place on the page, so I also wanted Jenkins to schedule a regular build regardless of whether or not code had been pushed, so ensure that my talks page would be up to date.

After originally thinking that I'd have to split the build steps into a separate item and trigger that from a scheduled item, and amend my git commit item accordingly, I found a **Build periodically** option that I could use within the same item, leaving it intact and not having to make amends.

I set this to `@daily` (the same `H H * * *` - `H` is a Jenkins thing), so that the build would be triggered automatically each day without a commit, and deploy any updates to the site.

![Setting Jenkins to periodically build a new version of the site.](/assets/images/blog/oliverdavies-uk-jenkins-git-timer.png)

## Next Steps

This workflow works great for one site, but as I roll out more Sculpin sites, I'd like to reduce duplication. I see this mainly as I’ll end up creating a separate `sculpin_build` item that’s decoupled from the site that it’s building, and instead passing variables such as environment, server name and docroot path as parameters in a parameterized build.

I'll probably also take the raw shell script out of Jenkins and save it in a text file that's stored locally on the server, and execute that via Jenkins. This means that I’d be able to store this file in a separate Git repository with my other Jenkins scripts and get the standard advantages of using version control.

## Update

Since publishing this post, I've added some more items to the original build script.

### Updating Composer

    if [ -f composer.json ]; then
        /usr/local/bin/composer install
    fi

Updates project dependencies via [Composer](https://getcomposer.org/doc/00-intro.md#introduction) if composer.json exists.

### Updating Sculpin Dependencies

    if [ -f sculpin.json ]; then
      sculpin install
    fi

Runs `sculpin install` on each build if the sculpin.json file exists, to ensure that the required custom bundles and dependencies are installed.

### Managing Redirects

    if [ -f scripts/redirects.php ]; then
        /usr/bin/php scripts/redirects.php
    fi

I've been working on a `redirects.php` script that generates redirects from a .csv file, after seeing similar things in the [Pantheon Documentation](https://github.com/pantheon-systems/documentation) and [That Podcast](https://github.com/thatpodcast/thatpodcast.io) repositories. This checks if that file exists, and if so, runs it and generates the source file containing each redirect.
