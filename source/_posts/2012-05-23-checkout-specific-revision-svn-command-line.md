---
title: Checkout a specific revision from SVN from the command line
slug: checkout-specific-revision-svn-command-line
tags:
  - svn
  - version-control
use: [posts]
redirect:
    - blog/checkout-specific-revision-svn-command-line/
---
{% block excerpt %}
How to checkout a specific revision from a SVN (Subversion) repository.
{% endblock %}

{% block content %}
If you're checking out the repository for the first time:

    svn checkout -r 1234 url://repository/path

If you already have the repository checked out:

    svn up -r 1234
{% endblock %}
