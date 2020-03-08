---
title: Quick Project Switching in PhpStorm
date: 2018-09-04
excerpt: How to quickly switch between projects in PhpStorm.
tags:
    - phpstorm
has_tweets: true
---
Following a recent conversation on Twitter with [socketwench](https://twitter.com/socketwench) about project switching in PhpStorm, I thought I’d document my workflow here.

Here is the original tweet and my initial response. I also have a lot of PhpStorm projects, and as I’m always working on multiple projects I regularly need to switch between them.

{% include 'tweet' with {
    content: '<p lang="en" dir="ltr">I think you can start typing and it will filter?</p>&mdash; Oliver Davies (@opdavies) <a href="https://twitter.com/opdavies/status/1034472920532365312?ref_src=twsrc%5Etfw">August 28, 2018</a>',
} %}

On the PhpStorm welcome screen that displays when you first open it, your recent projects are displayed on the left-hand side of the screen, and are filterable. That means that I can start typing a project name, e.g. `oli`, and I will only see projects that start with that input.

![The PhpStorm welcome screen with filters applied to the project list](/images/blog/quick-project-switching-phpstorm/welcome-screen.png){.with-border .with-padding}

That’s great when opening a project from scratch, but what about when we’re already within a project and just want to be able to switch to another?

{% include 'tweet' with {
    content: '<p lang="en" dir="ltr">You can also use &#39;Open recent&#39; within the actions list, and then filter the list of projects. <a href="https://t.co/k8G9iIQNP0">pic.twitter.com/k8G9iIQNP0</a></p>&mdash; Oliver Davies (@opdavies) <a href="https://twitter.com/opdavies/status/1034542753651281920?ref_src=twsrc%5Etfw">August 28, 2018</a>',
} %}

There’s also a way to access this list once PhpStorm is open, by clicking 'Open Recent' within the File menu. The issue here though is that this list is not filterable.

You can also access this list using the keyboard, though the 'Search everywhere' or 'Find action' panes, and these are filterable.

![Using the 'find action' pane to find 'Open Recent'](/images/blog/quick-project-switching-phpstorm/find-action.png){.with-border .with-padding}

Once the 'Open Recent' option is selected, you see the same project list as on the welcome screen, which is filtered in the same way by starting to type potential project names.

![The filtered project list](/images/blog/quick-project-switching-phpstorm/open-recent.png){.with-border .with-padding}

## Adding a Keyboard Shortcut

We can make this easier by adding a new keyboard shortcut. Within the Keymap preferences, you can search for 'Open Recent' and right-click it to add a new keyboard shortcut and define the key combination.

![Finding the 'Open Recent' shortcut in the Keymap preferences](/images/blog/quick-project-switching-phpstorm/adding-keyboard-shortcut-1.png){.with-border .with-padding}

![Assigning a keyboard shortcut](/images/blog/quick-project-switching-phpstorm/adding-keyboard-shortcut-2.png){.with-border .with-padding}

This this shortcut added, you can now use it to instantly bring up your recent projects list, filter it and switch project.
