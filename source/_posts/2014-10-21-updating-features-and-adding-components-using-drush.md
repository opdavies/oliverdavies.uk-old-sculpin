---
title: Updating Features and Adding Components Using Drush
slug: updating-features-and-adding-components-using-drush
tags:
  - drupal
  - drupal-planet
  - drush
  - features
use: [posts]
redirect:
    - blog/updating-features-and-adding-components-using-drush/
---
{% block excerpt %}
If you use the [Features module](http://drupal.org/project/features) to manage your Drupal configuration, it can be time consuming to update features through the UI, especially if you are working on a remote server and need to keep downloading and uploading files.

If you re-create a feature through the UI, you'll be prompted to download a new archive of the feature in its entirety onto your local computer. You could either commit this into a local repository and then pull it remotely, or use a tool such as SCP to upload the archive onto the server and commit it from there. You can simplify this process by using [Drush](http://drush.org).
{% endblock %}

{% block content %}
If you use the [Features module](http://drupal.org/project/features) to manage your Drupal configuration, it can be time consuming to update features through the UI, especially if you are working on a remote server and need to keep downloading and uploading files.

If you re-create a feature through the UI, you'll be prompted to download a new archive of the feature in its entirety onto your local computer. You could either commit this into a local repository and then pull it remotely, or use a tool such as SCP to upload the archive onto the server and commit it from there. You can simplify this process by using [Drush](http://drush.org).
## Finding Components

To search for a component, use the `drush features-components` command. This will display a list of all components on the site. As we're only interested in components that haven't been exported yet, add the `--not-exported` option to filter the results.

To filter further, you can also use the `grep` command to filter the results. For example, `drush features-components --not-exported field_base | grep foo`, would only return non-exported field bases containing the word "foo".

The result is a source and a component, separated by a colon. For example,  `field_base:field_foo`.

## Exporting the Feature

Once you have a list of the components that you need to add, you can export the feature. This is done using the `drush features-export` command, along with the feature name and the component names.

For example:

    $ drush features-export -y myfeature field_base:field_foo field_instance:user-field_foo

In this example, the base for field_boo and it's instance on the user object is being added to the "myfeature" feature.

If you are updating an existing feature, you'll get a message informing you that the module already exists and asking if you want to continue. This is fine, and is automatically accepted by including `-y` within the command. If a feature with the specified name doesn't exist, it will be created.

If you're creating a new feature, you can define where the feature will be created using the `--destination` option.

Once complete, you will see a confirmation message.

> Created module: my feature in sites/default/modules/custom/features/myfeature

## The Result

Once finished, the feature is updated in it's original location, so there's no download of the feature and then needing to re-upload it. You can add and commit your changes into Git or continue with your standard workflow straight away.

## Useful Links

* [The Features project page on Drupal.org](http://www.drupal.org/project/features)
* [The "drush features-components" command](http://www.drushcommands.com/drush-6x/features/features-components)
* [The "drush features-export" command](http://www.drushcommands.com/drush-6x/features/features-export)
{% endblock %}
