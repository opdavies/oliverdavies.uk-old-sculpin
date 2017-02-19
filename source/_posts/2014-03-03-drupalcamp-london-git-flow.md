---
title: DrupalCamp London: What is Git Flow?
description: Slides and notes from my talk, "What is Git Flow?", that I presented at DrupalCamp London 2014.
slug: what-git-flow
tags:
  - git
  - git-flow
  - drupalcamp-london
  - talks
tweets: true
use: [posts]
redirect:
    - blog/drupalcamp-london-git-flow/
---
{% block excerpt %}
Here are my slides from my "What is Git Flow?" session at [DrupalCamp London](http://2014.drupalcamplondon.co.uk).
{% endblock %}

{% block content %}
Here are my slides from my "What is Git Flow?" session at [DrupalCamp London](http://2014.drupalcamplondon.co.uk).

{% include 'speakerdeck' with { data_id: '201559e0f103013198dd5a5f6f23ab67' } %}

## Take aways

The main take aways are:

* Git Flow adds various commands into Git to enhance its native functionality, which creates a branching model to separate your stable production code from your unstable development code.
* Never commit directly to the master branch - this is for production code only!
* You can commit directly to the develop branch, but this should be done sparingly.
* Use feature branches as much as possible - one per feature, user story or bug.
* Commit early and often, and push to a remote often to encourage collaboration as well as to provide a backup of your code.
* You can use settings within services like GitHub and Bitbucket to only allow certain users to push to the master and develop branches, and restrict other Developers to only commit and push to feature branches. Changes can then be committed and pushed, then reviewed as part of a peer code review, and merged back into the develop branch.

## Feedback

If you've got any questions, please feel free to <a href="http://twitter.com/opdavies" title="My Twitter account">tweet at me</a> or fill in the <a href="http://2014.drupalcamplondon.co.uk/node/add/session-evaluation?nid=86&destination=node/86" title="The session evaluation form to submit feedback">session evaluation form</a> that you can complete on the DrupalCamp London website.

I've had some great feedback via Twitter:

{% include 'tweet' with {
  content: '<p><a href="https://twitter.com/opdavies">@opdavies</a> <a href="https://twitter.com/DrupalCampLDN">@DrupalCampLDN</a> always had trouble with git. Your talk + Git flow has made it all very easy.</p>&mdash; James Tombs (@jtombs) <a href="https://twitter.com/jtombs/statuses/440108072078696449">March 2, 2014</a>'
} %}

{% include 'tweet' with {
  content: '<p>Great presentation by <a href="https://twitter.com/opdavies">@opdavies</a> on git flow at <a href="https://twitter.com/search?q=%23dclondon&amp;src=hash">#dclondon</a> very well prepared and presented. <a href="http://t.co/tDINp2Nsbn">pic.twitter.com/tDINp2Nsbn</a></p>&mdash; Greg Franklin (@gfranklin) <a href="https://twitter.com/gfranklin/statuses/440104311276969984">March 2, 2014</a>'
} %}

{% include 'tweet' with {
  content: '<p>Great talk on git flow <a href="https://twitter.com/opdavies">@opdavies</a> <a href="https://twitter.com/search?q=%23dclondon&amp;src=hash">#dclondon</a></p>&mdash; Curve Agency (@CurveAgency) <a href="https://twitter.com/CurveAgency/statuses/440095250775035904">March 2, 2014</a>'
} %}
{% endblock %}
