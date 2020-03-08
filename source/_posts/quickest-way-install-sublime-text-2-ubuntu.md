---
title: The Quickest way to Install Sublime Text 2 in Ubuntu
date: 2013-03-02
excerpt: After reading numerous blog posts about how to install Sublime Text 2 in Ubuntu, this is definitely the quickest way!
tags:
  - linux
  - sublime-text
  - ubuntu
---
After reading numerous blog posts about how to install [Sublime Text 2](http://www.sublimetext.com/2 "Sublime Text 2") in [Ubuntu](http://www.ubuntu.com/2 "Ubuntu"), this is definitely the quickest way!

Just paste the following lines into your Terminal:

```language-bash
$ sudo add-apt-repository ppa:webupd8team/sublime-text-2
$ sudo apt-get update
$ sudo apt-get install sublime-text
```

After running this, Sublime Text 2 has been installed within the */usr/lib/sublime-text-2* directory and can be launched from the Dashboard, or by typing `subl`, `sublime-text` or `sublime-text-2` into a Terminal window.
