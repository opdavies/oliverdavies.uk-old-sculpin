---
title: Drupal VM Generator 2.9.1 Released
tags: ['drupal-vm-generator', releases]
use: [posts]
redirect:
    - blog/drupal-vm-generator-updates/
---
{% block excerpt %}
I spent some time yesterday working on the [Drupal VM Generator][0], and have released versions 2.8.1, 2.9.0 and 2.9.1.
{% endblock %}

{% block content %}
I spent some time yesterday working on the [Drupal VM Generator][0], and have released versions 2.8.1, 2.9.0 and 2.9.1.

The main updates are:

- Fixed an `InvalidResponseException` that was thrown from within the `boolean_as_string` Twig filter from the opdavies/twig-extensions library when the `config:generate` command was run in non-interactive mode.
- Adding a working test suite for the existing commands, using PhpUnit and Symfony’s Process component. This is now linked to [Travis CI][2], and the tests are run on each commit and pull request.
- The version requirements have been changed to allow 2.7 versions of the used Symfony Components, as well as the 3.x versions. This was done to resolve a conflict when also installing Drush globally with Composer.

## Next Steps

Currently the project is based on Drupal VM 3.0.0 which is an outdated version ([4.1.0][3] was released today). Adding updates and supporting the newer versions is a high priority, as well as keeping in sync with new releases. This will be easier with the test suite in place.

My initial thoughts are that version 2.10.0 will support Drupal VM 4.0.0, and if needed, 2.11.0 will ship shortly afterwards and support Drupal VM 4.1.0.
{% endblock %}

[0]: http://www.drupalvmgenerator.com
[1]: https://github.com/opdavies/drupal-vm-generator/tree/master/tests/Command
[2]: https://travis-ci.org/opdavies/drupal-vm-generator
[3]: https://github.com/geerlingguy/drupal-vm/releases/tag/4.1.0
