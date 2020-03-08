---
title: Easier Git Repository Cloning with insteadOf
date: 2019-03-07
excerpt: How to simplify 'git clone' commands by using the insteadOf configuration option within your .gitconfig file.
tags: [git]
---
When working on client or open source projects, I clone a lot of [Git](https://git-scm.com) repositories - either from GitHub, GitLab, Bitbucket or Drupal.org.
The standard `git clone` commands though provided by these sites can be quite verbose with long repository URLs and use a mixture of different protocols, and I’d regularly need to go back to each website and look up the necessary command every time.

For example, here is the command provided to clone Drupal’s [Override Node Options module](https://www.drupal.org/project/override_node_options):

```plain
git clone --branch 8.x-2.x https://git.drupal.org/project/override_node_options.git
```

We can though simplify the command to make it easier and quicker to type, using a Git configuration option called `insteadOf`.

## What is insteadOf?

From the [Git documentation](https://git-scm.com/docs/git-config#git-config-urlltbasegtinsteadOf):

> **url.[base].insteadOf:**
>
> Any URL that starts with this value will be rewritten to start, instead, with [base]. In cases where some site serves a large number of repositories, and serves them with multiple access methods, and some users need to use different access methods, this feature allows people to specify any of the equivalent URLs and have Git automatically rewrite the URL to the best alternative for the particular user, even for a never-before-seen repository on the site. When more than one insteadOf strings match a given URL, the longest match is used.

Whilst examples are sparse, [it seems like](https://stackoverflow.com/questions/1722807/how-to-convert-git-urls-to-http-urls) insteadOf is used for resolving protocol issues with repository URLs. However, we can use it to simplify our clone commands, as mentioned above.

## Example: cloning Drupal contrib projects

When working on Drupal core, or on a module, theme or distribution, you need to have a cloned version of that repository to generate patch files from, and apply patches to.

Again, here is the provided command to clone the Override Node Options module:

```plain
git clone --branch 8.x-2.x https://git.drupal.org/project/override_node_options.git
```

At the time of writing, the Git repository URL follow this same format - `https://git.drupal.org/project/{name}.git` (also the `.git` file extension is optional).

To shorten and simplify this, I can add this snippet to my `~/.gitconfig` file:

```
[url "https://git.drupal.org/project/"]
    insteadOf = do:
    insteadOf = drupal:
```

With that added, I can now instead run `git clone drupal:{name}` or `git clone do:{name}` to clone the repository, specifying the project’s machine name.

For example, to clone the Override Node Options module, I can now do this using just `git clone drupal:override_node_options`.

This, I think, is definitely quicker and easier!

## Resources

You can view my entire `.gitconfig` file, as well as my other dotfiles, in [my dotfiles repository on GitHub](https://github.com/opdavies/dotfiles/blob/master/.gitconfig).
