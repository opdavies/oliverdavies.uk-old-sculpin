---
title: The Quickest way to Install Sublime Text 2 in Ubuntu
slug: quickest-way-install-sublime-text-2-ubuntu
tags:
  - linux
  - sublime-text
  - ubuntu
use: [posts]
redirect:
    - blog/quickest-way-install-sublime-text-2-ubuntu/
---
{% block excerpt %}
After reading numerous blog posts about how to install [Sublime Text 2](http://www.sublimetext.com/2 "Sublime Text 2") in [Ubuntu](http://www.ubuntu.com/2 "Ubuntu"), this is definitely the quickest way!
{% endblock %}

{% block content %}
After reading numerous blog posts about how to install [Sublime Text 2](http://www.sublimetext.com/2 "Sublime Text 2") in [Ubuntu](http://www.ubuntu.com/2 "Ubuntu"), this is definitely the quickest way! Just paste the following lines into your Terminal:

    $ sudo add-apt-repository ppa:webupd8team/sublime-text-2
    $ sudo apt-get update
    $ sudo apt-get install sublime-text

After running this, Sublime Text 2 has been installed within the */usr/lib/sublime-text-2* directory and can be launched from the Dashboard, or by typing `subl`, `sublime-text` or `sublime-text-2` into a Terminal window.
{% endblock %}
