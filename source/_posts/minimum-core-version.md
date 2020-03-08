---
title: How to Define a Minimum Drupal Core Version
date: 2015-04-03
excerpt: How to define a minimum Drupal core version for your module or theme.
tags:
  - drupal
  - drupal-7
  - drupal-planet
meta:
  og:
    title: 'How to Define a Minimum Drupal Core Version'
    excerpt: 'How to define a minimum Drupal core version for your module or theme.'
    type: article
---
This week, my first code patch was [committed to Drupal core](https://www.drupal.org/node/2394517#comment-9773143). The patch adds the `user_has_role()` function to the user module, to simplify the way to check whether a user in Drupal has been assigned a specific role. This is something that I normally write a custom function for each project, but it's now available in Drupal core as of [7.36](https://www.drupal.org/drupal-7.36-release-notes).

But what if someone is using a core version less than 7.36 and tries using the function? The site would return an error because that function wouldn't exist.

If you're building a new Drupal site, then I'd assume that you're using a latest version of core, or you have the opportunity to update it when needed. But what if you're writing a contrib module? How can you be sure that the correct minimum version of core?

## Setting Dependencies

What I'm going to be doing for my contrib projects is defining a minimum version of Drupal core that the module is compatible with. If this dependency isn't met, the module won't be able to be enabled. This is done within your module's .info file.

### Adding a Simple Dependency

You can define a simple dependency for your module by adding a line this this to your project's .info file:

```language-bash
dependencies[] = views
```

This would make your module dependant on having the [Views](https://www.drupal.org/project/views) module present and enabled, which you'd need if you were including views as part of your module, for example.

### Adding a Complex Dependency

In the previous example, our module would enable if _any_ version of Views was enabled, but we need to specify a specific version. We can do this by including version numbers within the dependencies field in the following format:

```language-bash
dependencies[] = modulename (major.minor)
```

This can be a for a specific module release or a branch name:

```language-bash
dependencies[] = modulename (1.0)
dependencies[] = modulename (1.x)
```

We can also use the following as part of the field for extra granularity:

* = or == equals (this is the default)
* > greater than
* < lesser than
* >= greater than or equal to
* <= lesser than or equal to
* != not equal to

In the original scenario, we want to specify that the module can only be enabled on Drupal core 7.36 or later. To do this, we can use the "greater than or equal to" option.

```language-ini
dependencies[] = system (>=7.36)
```

Because we need to check for Drupal's core version, we're using the system module as the dependency and specifying that it needs to be either equal to or greater than 7.36. If this dependency is not met, e.g. Drupal 7.35 is being used, then the module cannot be enabled rather than showing a function not found error for `user_has_role()` when it is called.

![A screenshot of the modules page showing System as a dependency for a custom module.](/images/blog/minimum-drupal-version-d7.png)

## External Links

* [Writing module .info files (Drupal 7.x)](https://www.drupal.org/node/542202#dependencies)
