---
title: Announcing the Drupal VM Generator
tags:
    - drupal
    - drupal-planet
    - drupal-vm
    - drupal-vm-generator
    - symfony
use: [posts]
redirect:
    - blog/announcing-the-drupal-vm-generator/
---
{% block excerpt %}
For the past few weeks I’ve been working on a personal side project, based on Drupal VM. It’s called the [Drupal VM Generator][1], and over the weekend I’ve added the final features and fixed the remaining issues, and tagged the 1.0.0 release.
{% endblock %}

{% block content %}
For the past few weeks I’ve been working on a personal side project, based on Drupal VM. It’s called the [Drupal VM Generator][1], and over the weekend I’ve added the final features and fixed the remaining issues, and tagged the 1.0.0 release.

![](/assets/images/blog/drupalvm-generate-repo.png)

## What is Drupal VM?

[Drupal VM][2] is a project created and maintained by [Jeff Geerling][3]. It’s a [Vagrant][4] virtual machine for Drupal development that is provisioned using [Ansible][5].

What is different to a regular Vagrant VM is that uses a file called `config.yml` to configure the machine. Settings such as `vagrant_hostname`, `drupalvm_webserver` and `drupal_core_path` are stored as YAML and passed into the `Vagrantfile` and the `playbook.yml` file which is used when the Ansible provisioner runs.

In addition to some essential Ansible roles for installing and configuring packages such as Git, MySQL, PHP and Drush, there are also some roles that are conditional and only installed based on the value of other settings. These include Apache, Nginx, Solr, Varnish and Drupal Console.

## What does the Drupal VM Generator do?

> The Drupal VM Generator is a Symfony application that allows you to quickly create configuration files that are minimal and use-case specific.

Drupal VM comes with an [example.config.yml file][6] that shows all of the default variables and their values. When I first started using it, I’d make a copy of `example.config.yml`, rename it to `config.yml` and edit it as needed, but a lot of the examples aren’t needed for every use case. If you’re using Nginx as your webserver, then you don’t need the Apache virtual hosts. If you are not using Solr on this project, then you don’t need the Solr variables.

For a few months, I’ve kept and used boilerplace versions of `config.yml` - one for Apache and one for Nginx. These are minimal, so have most of the comments removed and only the variables that I regularly need, but these can still be quite time consuming to edit each time, and if there are additions or changes upstream, then I have two versions to maintain.

The Drupal VM Generator is a Symfony application that allows you to quickly create configuration files that are minimal and use-case specific. It uses the [Console component][7] to collect input from the user, [Twig][8] to generate the file, the [Filesystem component][9] to write it.

Based on the options passed to it and/or answers that you provide, it generates a custom, minimal `config.yml` file for your project.

Here’s an example of it in action:

!['An animated gif showing the interaction process and the resulting config.yml file'](/assets/images/blog/drupalvm-generate-example-2.gif)

You can also define options when calling the command and skip any or all questions. Running the following would bypass all of the questions and create a new file with no interaction or additional steps.

{{ gist('24e569577ca4b72f049d', 'with-options.sh') }}

## Where do I get it?

The project is hosted on [GitHub][1], and there are installation instructions within the [README][10].

<div class="github-card" data-github="opdavies/drupal-vm-generator" data-width="400" data-height="" data-theme="default"></div>

The recommended method is via downloading the phar file (the same as Composer and Drupal Console). You can also clone the GitHub repository and run the command from there. I’m also wanting to upload it to Packagist so that it can be included if you manage your projects with Composer.

Please log any bugs or feature requests in the [GitHub issue tracker][11], and I’m more than happy to receive pull requests.

If you’re interested in contributing, please feel free to fork the repository and start doing so, or contact me with any questions.

__Update 17/02/16:__ The autoloading issue is now fixed if you require the package via Composer, and this has been tagged as the [1.0.1 release][12]
{% endblock %}

[1]: https://github.com/opdavies/drupal-vm-generator
[2]: http://www.drupalvm.com
[3]: http://www.jeffgeerling.com
[4]: http://www.vagrantup.com
[5]: https://www.ansible.com
[6]: https://github.com/geerlingguy/drupal-vm/blob/master/example.config.yml
[7]: http://symfony.com/doc/current/components/console/introduction.html
[8]: http://twig.sensiolabs.org
[9]: http://symfony.com/doc/current/components/filesystem/introduction.html
[10]: https://github.com/opdavies/drupal-vm-generator/blob/master/README.md#installation
[11]: https://github.com/opdavies/drupal-vm-generator/issues
[12]: https://github.com/opdavies/drupal-vm-generator/releases/tag/1.0.1
