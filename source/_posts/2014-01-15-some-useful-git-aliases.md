---
title: Some Useful Git Aliases
tags:
  - git
use: [posts]
redirect:
    - blog/some-useful-git-aliases/
---
{% block excerpt %}
Here are some bash aliases that I use and find helpful for quickly writing Git and Git Flow commands.
{% endblock %}

{% block content %}
Here are some bash aliases that I use and find helpful for quickly writing Git and Git Flow commands. These should be placed within your `~/.bashrc` or `~/.bash_profile` file:

    alias gi="git init"
    alias gcl="git clone"
    alias gco="git checkout"
    alias gs="git status"
    alias ga="git add"
    alias gaa="git add --all"
    alias gc="git commit"
    alias gcm="git commit -m"
    alias gca="git commit -am"
    alias gm="git merge"
    alias gr="git rebase"
    alias gps="git push"
    alias gpl="git pull"
    alias gd="git diff"
    alias gl="git log"
    alias gfi="git flow init"
    alias gff="git flow feature"
    alias gfr="git flow release"
    alias gfh="git flow hotfix"
{% endblock %}
