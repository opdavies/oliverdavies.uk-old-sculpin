---
title: Turning Your Custom Drupal Module into a Feature
date: 2017-05-20
excerpt: How to turn a custom Drupal module into a Feature.
tags:
    - drupal
    - drupal-7
    - drupal-planet
    - features
---
Yesterday I was fixing a bug in an inherited Drupal 7 custom module, and I decided that I was going to add some tests to ensure that the bug was fixed and doesn’t get accidentially re-introduced in the future. The test though required me to have a particular content type and fields which are specific to this site, so weren’t present within the standard installation profile used to run tests.

I decided to convert the custom module into a [Feature][0] so that the content type and it’s fields could be added to it, and therefore present on the testing site once the module is installed.

To do this, I needed to expose the module to the Features API.

All that’s needed is to add this line to the `mymodule.info` file:

```language-ini
features[features_api][] = api:2
```

After clearing the cache, the module is now visible in the Features list - and ready to have the appropriate configuration added to it.

!['The features list showing the custom module'](/images/blog/custom-module-as-a-feature.png)

[0]: https://www.drupal.org/project/features
