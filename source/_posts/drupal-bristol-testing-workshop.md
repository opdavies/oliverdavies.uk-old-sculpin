---
title: Drupal Bristol Testing Workshop
date: 2018-06-28
excerpt: Yesterday evening, I did my first workshop, held at the Drupal Bristol user group.
tags: [composer, docksal, drupal, drupal-8, drupal-bristol, php, phpunit, testing]
---
Yesterday evening, I did [my first workshop][16] (and I believe, the first workshop) held at the [{{ site.events['drupal_bristol'].name }}][14] user group. The subject was automated testing with PHPUnit in Drupal 8, in preparation for my talk at [{{ site.events['drupal_dev_days_18'].name }}][12] next week and to help process some ideas for my [testing book][15].

Here are some details about what we covered, and some of my thoughts in review.

## Local Environment

Before the meetup, I set up a [repository on GitHub][0] that contains a Composer-based Drupal 8 installation, based on the [Drupal 8 Composer template][4] along with the [Examples module][5] (which includes some PHPUnit tests) with a pre-configured [Docksal][2] environment to use locally - Docksal being our standard local development environment that we use at {{ site.companies['microserve'].name }} for all of our projects, so something that I’m familiar with using.

In addition to the default stack, I added [the PHPUnit add-on that I wrote][6] so that it was easier to run tests, [configured settings.php using environment variables][7] and added a custom `fin init` command to install the Composer dependencies and install Drupal. This meant after that installing Docksal, everyone had a running Drupal 8 website after only running `git clone` and `fin init`, and could then run tests straight away using `fin phpunit web/modules/contrib/examples/phpunit_example`.

## Exercises

Once everyone was set up, we moved on to talk about why testing is important and the different options available to run them, before looking at the different types of tests available in Drupal 8. For each test type, I explained what it was used for and everyone completed an exercise on each - creating a test of that type, initially seeing it fail, and then doing the work to get it to pass.

The completed code that I wrote beforehand for these is available in their own [GitHub repository][8], including all of the tests as well as the implementation code.

Once these exercises were completed, we looked at creating a blog page using test driven development - the example that I use in the [TDD - Test Driven Drupal talk][9], to give a more real-word scenario. It would have been good to have gone through this as an exercise too, if we’d have had more time.

## Wrap Up

To finish, I demonstrated the PHPUnit integration within PHPStorm (which is working with Docksal) and showed some of the tests that I wrote for the [Private Message Queue][10] and [System User][11] modules, to see how things like adding items to queues and processing them, ensuring that emails are sent, to the right users and contain the right data, can be tested, as well as some of the tests that we’ve written on my work project over the last few months.

## Slides

I didn’t record this workshop, but I have exported the slides and embedded them below:

{% include 'speakerdeck' with {
    data: {
        id: '2679401cb2ad421789d372cb8d38e368',
        ratio: '1.77777777777778',
    }
} %}

## Thoughts

I was very happy with how my first workshop went, it was a great experience for me and it seemed that the attendees all learnt something and found it interesting.

A couple of people mentioned about providing handouts to refer the code examples whilst working on the exercises, rather than relying on the slides and avoiding the need to sometimes switch back and forth between slides. I’ve found that I can export the slide deck as PNGs or JPGs from Deckset, so I’ll definitely do that next time.

I’m giving the [Test Driven Drupal][9] talk at the [Drupal Dev Days conference][12] next week, and I’m hoping to give it again at other meetups and events in the UK. If you’d like me to do either at your meetup or event, [get in touch][13].

[0]: https://github.com/opdavies/drupal-testing-workshop
[1]: https://github.com/drupal-composer/drupal-project
[2]: https://docksal.io
[3]: {{site.companies['microserve'].url}}
[4]: https://github.com/drupal-composer/drupal-project
[5]: https://www.drupal.org/project/examples
[6]: /articles/creating-a-custom-phpunit-command-for-docksal
[7]: /articles/using-environment-variables-settings-docksal
[8]: https://github.com/opdavies/drupal-testing-workshop-exercises
[9]: /talks/tdd-test-driven-drupal
[10]: https://www.drupal.org/project/private_message_queue
[11]: https://www.drupal.org/project/system_user
[12]: {{site.events.drupal_dev_days_18.url}}
[13]: /contact
[14]: {{site.events.drupal_bristol.url}}
[15]: /test-driven-drupal
[16]: https://groups.drupal.org/node/520891
