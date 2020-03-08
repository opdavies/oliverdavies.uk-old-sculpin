---
title: git format-patch is your Friend
date: 2014-05-21
excerpt: An explanation of the "git format-patch" command, and how it could be used in Drupal's Git workflow.
tags:
  - patches
  - drupal
  - drupal-planet
  - git
---
An explanation of the "git format-patch" command, and how it could be used in Drupal's Git workflow.

## The Problem

As an active contributor to the [Drupal](http://drupal.org) project, I spend a lot of time working with other peoples’ modules and themes, and occassionally have to fix a bug or add some new functionality.

In the Drupal community, we use a patch based workflow where any changes that I make get exported to a file detailing the differences. The patch file (*.patch) is attached to an item in an issue queue on Drupal.org, applied by the maintainer to their local copy of the code and reviewed, and hopefully committed.

There is an option that the maintainer can add to the end of their commit message.

For example:

```language-bash
--author="opdavies <opdavies@381388.no-reply.drupal.org>"
```

This differs slightly different for each Drupal user, and the code can be found on their Drupal.org profile page.

If this is added to the end of the commit message, the resulting commit will show that it was committed by the maintainer but authored by a different user. This will then display on Drupal.org that you’ve made a commit to that project.

![A screenshot of a commit that was authored by rli but committed by opdavies](/images/blog/git-format-patch.png)

The problem is that some project maintainers either don’t know about this option or occasionally forget to add it. [Dreditor](http://dreditor.org) can suggest a commit message and assign an author, but it is optional and, of course, not all maintainers use Dreditor (although they probably should).

The `git format-patch` command seems to be the answer, and will be my preferred method for generating patch files in the future rather than `git diff`.

## What does it do Differently?

From the [manual page](http://git-scm.com/docs/git-format-patch):

> Prepare each commit with its patch in one file per commit, formatted to resemble UNIX mailbox format. The output of this command is convenient for e-mail submission or for use with git am.

Here is a section of a patch that I created for the [Metatag module](http://drupal.org/project/metatag) using `git format-patch`:

```language-bash
From 80c8fa14de7f4a83c2e70367aab0aedcadf4f3b0 Mon Sep 17 00:00:00 2001
From: Oliver Davies &lt;oliver@oliverdavies.co.uk&gt;
Subject: [PATCH] Exclude comment entities when checking if this is the page,
 otherwise comment_fragment.module will break metatag
---
```

As mentioned above, the patch is structured in an email format. The commit message is used as the subject line, and the date that the commit was made locally is used for the date. What we’re interested in is the “From” value. This contains your name and email address from your `~/.gitconfig` file and is used to author the patch automatically.

Everything below this is the same as a standard patch file, the same as if was generated with `git diff`.

The full patch file can be found at <https://drupal.org/files/issues/metatag-comment-fragment-conflict-2265447-4.patch>.

## The Process

How did I create this patch? Here are the steps that I took:

1. Clone the source repository using `$ git clone --branch 7.x-1.x http://git.drupal.org/project/metatag.git` and move into that directory.
2. Create a branch for this patch using `$ git checkout -b 2265447-comment-fragment-conflict`.
3. Add and commit any changes as normal.
4. Generate the patch file using `$ git format-patch 7.x-1.x --stdout > metatag-comment-fragment-conflict-2265447-4.patch`.

*Note:* I am defining 7.x-1.x in the last step as the original branch to compare (i.e. the original branch that we forked to make our issue branch). This will change depending on the project that you are patching, and it’s version number. Also, commits should always be made against the development branch and not the stable release.

By default, a separate patch file will be created for each commit that we’ve made. This is overridden by the `--stdout` option which combines all of the patches into a single file. This is the recommended approach when uploading to Drupal.org.

The resulting patch file can be uploaded onto a Drupal.org issue queue, reviewed by the Testbot and applied by a module maintainer, and you automatically get the commit attributed. Problem solved.

## Committing the Patch

If you need to commit a patch that was created using `git format-patch`, the best command to do this with is the `git am` command.

For example, within your repository, run:

```language-bash
$ git am /path/to/file
$ git am ~/Code/metatag-comment-fragment-conflict-2265447-4.patch
```

You should end up with some output similar to the following:

```language-bash
Applying: #2272799 Added supporters section
Applying: #2272799 Added navigation tabs
Applying: #2272799 Fixed indentation
Applying: #2272799 Replaced URL
```

Each line is the commit message associated with that patch.

Assuming that there are no errors, you can go ahead and push your updated code into your remote repository.
