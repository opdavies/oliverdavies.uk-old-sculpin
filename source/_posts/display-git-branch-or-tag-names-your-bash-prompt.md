---
title: Display Git Branch or Tag Names in your Bash Prompt
date: 2013-04-27
excerpt: Whilst watching Drupalize.me's recent Introduction to Git series, I thought it was useful the way that the current Git branch or tag name was displayed in the bash prompt. Here's how to do it.
tags:
  - drupal
  - drupal-planet
  - git
  - terminal
---
Whilst watching [Drupalize.me](http://drupalize.me "Drupalize.me")'s recent [Introduction to Git series](http://drupalize.me/series/introduction-git-series "Introduction to Git on Drupalize.me"), I thought it was useful the way that the current Git branch or tag name was displayed in the bash prompt.

Here's how to do it.

For example (with some slight modifications):

```language-bash
oliver@oliver-mbp:~/Development/drupal(master) $
oliver@oliver-mbp:~/Development/a11y_checklist(7.x-1.0) $
```

Here's how to do it.

To begin with, create a new file to contain the functions,

```language-bash
vim ~/.bash/git-prompt
```

Paste the following code into the file, and save it.

```language-bash
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
```

Edit your `.bashrc` or `.bash_profile` file to override the PS1 value.

```language-bash
vim ~/.bashrc
```

Add  the following code at the bottom of the file, and save it.

```language-bash
source ~/.bash/git-prompt
PS1="\u@\h:\w\$(parse_git_branch_or_tag) $ "
```

Restart your Terminal or type `source ~/.bashrc` to see your changes.
