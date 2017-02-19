---
title: Display Git Branch or Tag Names in your Bash Prompt
description: Whilst watching Drupalize.me's recent Introduction to Git series, I thought it was useful the way that the current Git branch or tag name was displayed in the bash prompt. Here's how to do it.
slug: display-git-branch-or-tag-names-your-bash-prompt
tags:
  - drupal
  - drupal-planet
  - git
  - terminal
use: [posts]
redirect:
    - blog/display-git-branch-or-tag-names-your-bash-prompt/
---
{% block excerpt %}
Whilst watching [Drupalize.me](http://drupalize.me "Drupalize.me")'s recent [Introduction to Git series](http://drupalize.me/series/introduction-git-series "Introduction to Git on Drupalize.me"), I thought it was useful the way that the current Git branch or tag name was displayed in the bash prompt. Here's how to do it.
{% endblock %}

{% block content %}
Whilst watching [Drupalize.me](http://drupalize.me "Drupalize.me")'s recent [Introduction to Git series](http://drupalize.me/series/introduction-git-series "Introduction to Git on Drupalize.me"), I thought it was useful the way that the current Git branch or tag name was displayed in the bash prompt.

For example (with some slight modifications):

~~~~
oliver@oliver-mbp:~/Development/drupal(master) $
oliver@oliver-mbp:~/Development/a11y_checklist(7.x-1.0) $
~~~~

Here's how to do it.

To begin with, create a new file to contain the functions,

    vim ~/.bash/git-prompt

Paste the following code into the file, and save it.

~~~~
parse_git_branch () {
  git branch 2> /dev/null | sed -e '/^[^*]/d' -e 's/* \(.*\)/ (\1)/'
}

parse_git_tag () {
  git describe --tags 2> /dev/null
}
 
parse_git_branch_or_tag() {
  local OUT="$(parse_git_branch)"
  if [ "$OUT" == " ((no branch))" ]; then
    OUT="($(parse_git_tag))";
  fi
  echo $OUT
}
~~~~

Edit your `.bashrc` or `.bash_profile` file to override the PS1 value.

    vim ~/.bashrc

Add  the following code at the bottom of the file, and save it.

~~~~
source ~/.bash/git-prompt
PS1="\u@\h:\w\$(parse_git_branch_or_tag) $ "
~~~~

Restart your Terminal or type `source ~/.bashrc` to see your changes.
{% endblock %}
