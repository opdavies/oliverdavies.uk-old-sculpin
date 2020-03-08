---
title: Null Users and System Users in Drupal
date: 2018-08-16
excerpt: Announcing the Null User and System User modules.
tags:
    - drupal
    - drupal-7
    - drupal-8
    - drupal-modules
    - drupal-planet
    - php
---
Have you ever needed to have a 'special user' to perform tasks on your Drupal site, such as performing actions based on an API request, or for sending an internal site message?

If you just create a new user, how do you identify that user going forward? Do you hard-code the 'magic' user ID in your custom code? What if the user has a different ID on different environments of your site? You could declare it in each environment’s settings file and retrieve it from there, but what then if you need to do the same on another site? That would mean some duplication of code - and something that could have been abstracted and re-used.

I had to do this recently, and rather than just duplicate the code I decided to make it into it’s own module - which then became two modules.

## System users

The [System User module][1] provides a re-usable, generic way to denote users as 'system users', which is not specific to a certain site or environment as this is value is stored against each individual user in the database.

'System user' is a term used in Linux, which I thought also applies well to this scenario.

From <https://www.ssh.com/iam/user/system-account>:

> A system account is a user account that is created by an operating system during installation and that is used for operating system defined purposes. System accounts often have predefiend user ids. Examples of system accounts include the root account in Linux.

A system user isn’t an account that we’d expect a person to log in with and perform routine tasks like updating content, but rather for the system (site) to use to perform tasks like the earlier examples.

### Declaring a user as a system user

System User module adds a base field to Drupal’s User entity, which determines whether or not each user is a system user - i.e. if this field is `TRUE`, that user is a system user. This means that users can easily be queried to identify which are system users, without having to rely on magic, environment and site specific user IDs. This also means that we can have multiple system users, if needed.

![](/images/blog/null-users-system-users/drupal-8-users-field-data-table.png){.border .p-1}

In the Drupal 8 version of the module, a `SystemUser` is a custom entity, that contains it’s own `create` method for creating new system users. This is a essentially a wrapper around `User::create()` that automatically sets the value of the system user field as part of the creation.

The original intention is that system users would always be created manually in an custom install or update hook, however since releasing the module, I’ve also added an install hook to the module to automatically create a new system user when the module is installed, basing the username on the site name.

There is also an open issue to add a Drush command to create a new system user, and I’d imagine I’ll also add a Drupal Console command too.

### Retrieving system users

Whilst you could easily write your own query that retrieves users based on the value of the system user field, but the module contains a `SystemUserManager` service that contains methods to do so. It also provides a static helper class that determines if a specified user is a system user by checking the value of the system user field.

```
// Retrieve the first system user.
$system_user = $this->systemUserManager->getFirst();

// Is the specified user a system user?
$is_system_user = SystemUserManager::isSystemUser($user);
```

But what do we return if there are no system users?
You could return `NULL` or `FALSE`, but I decided to take a different approach, which became the second module.

## Null users

The [Null User module][2] is an implementation of the [null object pattern][3] for users in Drupal 8. In this case, a [NullUser][4] is an extension of Drupal’s `AnonymousUserSession`, which means that it inherits sensible defaults to return for a non-existent User. Though, through inheritance, the `id`, `getRoles` and `hasPermission` methods are overridden to return relevant values.

```language-php
use Drupal\Core\Session\AnonymousUserSession;

class NullUser extends AnonymousUserSession {
  ...
}
```

Null User module is a dependency of System User in Drupal 8, so When no system user is found from the `getFirst()` method, a `NullUser` is returned. Whilst I could alternatively have returned `NULL` or `FALSE`, we then would need to check if the returned value was an object or not before calling methods on it.

```language-php
$system_user = $this->systemUserManager->getFirst(); // Returns NULL or FALSE.

// Need to check if a user was returned or not.
if (!$system_user) {
  return;
}

if ($system_user->isActive()) {
  ...
}
```

Because instead we’re returning a `NullUser`, which through class inheritance has the same methods and properties as a regular user, there is no need to do the additional check as you will always receive a relevant object, and the expected methods will always be present.

```language-php
$system_user = $this->systemUserManager->getFirst(); // Returns a NullUser.

if ($system_user->isActive()) {
  ...
}
```

This means we have less code, which also is simpler and more readable.

System User module is the only one that I’m aware of that makes use of Null User, but I’ve added a list to the [project page][2] so let me know if you can think of any others.

## Resources

- [Null object pattern][3]
- [Null User module][2]
- [System User module][1]

[1]: https://www.drupal.org/project/system_user
[2]: https://www.drupal.org/project/null_user
[3]: https://en.wikipedia.org/wiki/Null_object_pattern
[4]: http://cgit.drupalcode.org/null_user/tree/src/NullUser.php?h=8.x-1.x
