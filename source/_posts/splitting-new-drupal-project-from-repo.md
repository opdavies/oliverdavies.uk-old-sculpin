---
title: How to split a new Drupal contrib project from within another repository
date: 2018-03-10
excerpt: How to use Git to split a directory from within an existing repository into it’s own.
tags:
    - drupal
    - drupal-7
    - drupal-8
    - drupal-planet
    - git
    - open-source
---
Yay! You’ve written a new Drupal module, theme or installation profile as part of your site, and now you’ve decided to open source it and upload it to Drupal.org as a new contrib project. But how do you split it from the main site repository into it’s own?

Well, there are a couple of options.

## Does it need to be part of the site repository?

An interesting thing to consider is, does it _need_ to be a part of the site repository in the first place?

If from the beginning you intend to contribute the module, theme or distribution and it’s written as generic and re-usable from the start, then it _could_ be created as a separate project on Drupal.org or as a private repository on your Git server from the beginning, and added as a dependency of the main project rather than part of it. It could already have the correct branch name and adhere to the Drupal.org release conventions and be managed as a separate project, then there is no later need to "clean it up" or split it from the main repo at all.

This is how I worked at the [Drupal Association][2] - with all of the modules needed for Drupal.org hosted on Drupal.org itself, and managed as a dependency of the site repository with Drush Make.

Whether this is a viable option or not will depend on your processes. For example, if your code needs to go through a peer review process before releasing it, then pushing it straight to Drupal.org would either complicate that process or bypass it completely. Pushing it to a separate private repository may depend on your team's level of familiarity with [Composer][3], for example.

It does though avoid the “we’ll clean it up and contribute it later” scenario which probably happens less than people intend.

## Create a new, empty repository

If the project is already in the site repo, this is probably the most common method - to create a new, empty repository for the new project, add everything to it and push it.

For example:

```language-bash
cd web/modules/custom/my_new_module

# Create a new Git repository.
git init

# Add everything and make a new commit.
git add -A .
git commit -m 'Initial commit'

# Rename the branch.
git branch -m 8.x-1.x

# Add the new remote and push everything.
git remote add origin username@git.drupal.org:project/my_new_module.git
git push origin 8.x-1.x
```

There is a huge issue with this approach though - **you now have only one single commit, and you’ve lost the commmit history!**

This means that you lose the story and context of how the project was developed, and what decisions and changes were made during the lifetime of the project so far. Also, if multiple people developed it, now there is only one person being attributed - the one who made the single new commit.

Also, if I’m considering adding your module to my project, personally I’m less likely to do so if I only see one "initial commit". I’d like to see the activity from the days, weeks or months prior to it being released.

What this does allow though is to easily remove references to client names etc before pushing the code.

## Use a subtree split

An alternative method is to use [git-subtree][0], a Git command that "merges subtrees together and split repository into subtrees". In this scenario, we can use `split` to take a directory from within the site repo and split it into it’s own separate repository, keeping the commit history intact.

Here is the description for the `split` command from the Git project itself:

> Extract a new, synthetic project history from the
history of the <prefix> subtree.  The new history
includes only the commits (including merges) that
affected <prefix>, and each of those commits now has the
contents of <prefix> at the root of the project instead
of in a subdirectory.  Thus, the newly created history
is suitable for export as a separate git repository.

<div class="note" markdown="1">
__Note__: This command needs to be run at the top level of the repository. Otherwise you will see an error like "You need to run this command from the toplevel of the working tree.".

To find the path to the top level, run `git rev-parse --show-toplevel`.
</div>

In order to do this, you need specify the prefix for the subtree (i.e. the directory that contains the project you’re splitting) as well as a name of a new branch that you want to split onto.

```
git subtree split --prefix web/modules/custom/my_new_module -b split_my_new_module
```

When complete, you should see a confirmation message showing the branch name and the commit SHA of the branch.

```
Created branch 'split_my_new_module'
7edcb4b1f4dc34fc3b636b498f4284c7d98c8e4a
```

If you run `git branch`, you should now be able to see the new branch, and if you run `git log --oneline split_my_new_module`, you should only see commits for that module.

If you do need to tidy up a particular commit to remove client references etc, change a commit message or squash some commits together, then you can do that by checking out the new branch, running an interactive rebase and making the required amends.

```
git checkout split_my_new_module
git rebase -i --root
```

Once everything is in the desired state, you can use `git push` to push to the remote repo - specifying the repo URL, the local branch name and the remote branch name:

```
git push username@git.drupal.org:project/my_new_module.git split_my_new_module:8.x-1.x
```

In this case, the new branch will be `8.x-1.x`.

Here is a screenshot of example module that I’ve split and pushed to GitLab. Notice that there are multiple commits in the history, and each still attributed to it’s original author.

![Screenshot of a split project repo on GitLab](/images/blog/subtree-split-drupal-module.png)

Also, as this is standard Git functionality, you can follow the same process to extract PHP libraries, Symfony bundles, WordPress plugins or anything else.

[0]: https://github.com/git/git/blob/master/contrib/subtree/git-subtree.txt
[1]: https://github.com/git/git/blob/master/contrib/subtree/git-subtree.txt#L101-L108
[2]: {{site.companies.drupal_association.url}}
[3]: https://getcomposer.org
