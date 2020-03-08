---
title: Back to the future with Git’s diff and apply commands
date: 2018-04-23
excerpt: How to revert files using Git, but as a new commit to prevent force pushing.
tags:
    - git
---
This is one of those “there’s probably already a better way to do this” situations, but it worked.

I was having some issues this past weekend where, despite everything working fine locally, a server was showing a “500 Internal Server” after I pushed some changes to a site. In order to bring the site back online, I needed to revert the site files back to the previous version, but as part of a new commit.

The `git reset` commands removed the interim commits which meant that I couldn’t push to the remote (force pushing, quite rightly, isn’t allowed for the production branch), and using `git revert` was resulting in merge conflicts in `composer.lock` that I’d rather have avoided if possible.

This is what `git log --oneline -n 4` was outputting:

```
14e40bc Change webflo/drupal-core-require-dev version
fc058bb Add services.yml
60bcf33 Update composer.json and re-generate lock file
722210c More styling
```

`722210c` is the commit SHA that I needed to go back to.

## First Solution

My first solution was to use `git diff` to create a single patch file of all of the changes from the current point back to the original commit. In this case, I’m using `head~3` (four commits before `head`) as the original reference, I could have alternatively used a commit ID, tag or branch name.

```
git diff head head~3 > temp.patch
git apply -v temp.patch
```

With the files are back in the former state, I can remove the patch, add the files as a new commit and push them to the remote.

```
rm temp.patch

git add .
git commit -m 'Back to the future'
git push
```

Although the files are back in their previous, working state, as this is a new commit with a new commit SHA reference, there is no issue with the remote rejecting the commit or needing to attempt to force push.

## Second Solution

The second solution is just a shorter, cleaner version of the first!

Rather than creating a patch file and applying it, the output from `git diff` can be piped straight into `git apply`.

```
git diff head~3 head | git apply -v
```

This means that there’s only one command to run and no leftover patch file, and I can go ahead and add and commit the changes straight away.
