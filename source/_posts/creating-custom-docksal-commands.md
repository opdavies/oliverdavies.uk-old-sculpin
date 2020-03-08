---
title: Creating a Custom PHPUnit Command for Docksal
date: 2018-05-06
excerpt: How to write custom commands for Docksal, including one to easily run PHPUnit tests in Drupal 8.
tags:
    - docksal
    - drupal
    - drupal-8
    - drupal-planet
    - phpunit
    - testing
---
This week I’ve started writing some custom commands for my Drupal projects that use Docksal, including one to easily run PHPUnit tests in Drupal 8. This is the process of how I created this command.

## What is Docksal?

Docksal is a local Docker-based development environment for Drupal projects and other frameworks and CMSes. It is our standard tool for local environments for projects at [Microserve][0].

There was a [great talk][1] recently at Drupaldelphia about Docksal.

## Why write a custom command?

One of the things that Docksal offers (and is covered in the talk) is the ability to add custom commands to the Docksal’s `fin` CLI, either globally or as part of your project.

As an advocate of automated testing and TDD practitioner, I write a lot of tests and run PHPUnit numerous times a day. I’ve also given [talks][6] and have [written other posts][7] on this site relating to testing in Drupal.

There are a couple of ways to run PHPUnit with Docksal. The first is to use `fin bash` to open a shell into the container, move into the docroot directory if needed, and run the `phpunit` command.

```bash
fin bash
cd /var/www/docroot
../vendor/bin/phpunit -c core modules/custom
```

Alternatively, it can be run from the host machine using `fin exec`.

```
cd docroot
fin exec '../vendor/bin/phpunit -c core modules/custom'
```

Both of these options require multiple steps as we need to be in the `docroot` directory where the Drupal code is located before the command can be run, and both have quite long commands to run PHPUnit itself - some of which is repeated every time.

By adding a custom command, I intend to:

1. Make it easier to get set up to run PHPUnit tests - i.e. setting up a `phpunit.xml` file.
1. Make it easier to run the tests that we’d written by shortening the command and making it so it can be run anywhere within our project.

I also hoped to make it project agnostic so that I could add it onto any project and immediately run it.

## Creating the command

Each command is a file located within the `.docksal/commands` directory. The filename is the name of the command (e.g. `phpunit`) with no file extension.

To create the file, run this from the same directory where your `.docksal` directory is:

```bash
mkdir -p .docksal/commands
touch .docksal/commands/phpunit
```

This will create a new, empty `.docksal/commands/phpunit` file, and now the `phpunit` command is now listed under "Custom commands" when we run `fin`.

![](/images/blog/docksal-phpunit-command/1.gif)

You can write commands with any interpreter. I’m going to use bash, so I’ll add the shebang to the top of the file.

```bash
#!/usr/bin/env bash
```

With this in place, I can now run `fin phpunit`, though there is no output displayed or actions performed as the rest of the file is empty.

## Adding a description and help text

Currently the description for our command when we run `fin` is the default "No description" text. I’d like to add something more relevant, so I’ll start by adding a new description.


fin interprets lines starting with `##` as documentation - the first of which it uses as the description.

```bash
#!/usr/bin/env bash

## Run automated PHPUnit tests.
```

Now when I run it, I see the new description.

![](/images/blog/docksal-phpunit-command/2.gif)

Any additional lines are used as help text with running `fin help phpunit`. Here I’ll add an example command to demonstrate how to run it as well as some more in-depth text about what the command will do.

```bash
#!/usr/bin/env bash

## Run automated PHPUnit tests.
##
## Usage: fin phpunit <args>
##
## If a core/phpunit.xml file does not exist, copy one from elsewhere.
## Then run the tests.
```

Now when I run `fin help phpunit`, I see the new help text.

![](/images/blog/docksal-phpunit-command/3.gif)

## Adding some content

### Setting the target

As I want the commands to be run within Docksal’s "cli" container, I can specify that with `exec_target`. If one isn’t specified, the commands are run locally on the host machine.

```
#: exec_target = cli
```

### Available variables

These variables are provided by fin and are available to use within any custom commands:

- `PROJECT_ROOT` - The absolute path to the nearest `.docksal` directory.
- `DOCROOT` - name of the docroot folder.
- `VIRTUAL_HOST` - the virtual host name for the project. Such as `myproject.docksal`.
- `DOCKER_RUNNING` - (string) "true" or "false".

<div class="note" markdown="1">
**Note:** If the `DOCROOT` variable is not defined within the cli container, ensure that it’s added to the environment variables in `.docksal/docksal.yml`. For example:

```
version: "2.1"

services:
  cli:
    environment:
      - DOCROOT
```
</div>

### Running phpunit

When you run the `phpunit` command, there are number of options you can pass to it such as `--filter`, `--testsuite` and `--group`, as well as the path to the tests to execute, such as `modules/custom`.

I wanted to still be able to do this by running `fin phpunit <args>` so the commands can be customised when executed. However, as the first half of the command (`../vendor/bin/phpunit -c core`) is consistent, I can wrap that within my custom command and not need to type it every time.

By using `"$@"` I can capture any additional arguments, such as the test directory path, and append them to the command to execute.

I’m using `$PROJECT_ROOT` to prefix the command with the absolute path to `phpunit` so that I don’t need to be in that directory when I run the custom command, and `$DOCROOT` to always enter the sub-directory where Drupal is located. In this case, it’s "docroot" though I also use "web" and I’ve seen various others used.

```bash
DOCROOT_PATH="${PROJECT_ROOT}/${DOCROOT}"
DRUPAL_CORE_PATH="${DOCROOT_PATH}/core"

# If there is no phpunit.xml file, copy one from elsewhere.

# Otherwise run the tests.
${PROJECT_ROOT}/vendor/bin/phpunit -c ${DRUPAL_CORE_PATH} "$@"
```

For example, `fin phpunit modules/custom` would execute `/var/www/vendor/bin/phpunit -c /var/www/docroot/core modules/custom` within the container.

I can then wrap this within a condition so that the tests are only run when a `phpunit.xml` file exists, as it is required for them to run successfully.

```bash
if [ ! -e ${DRUPAL_CORE_PATH}/phpunit.xml ]; then
    # If there is no phpunit.xml file, copy one from elsewhere.
else
    ${PROJECT_ROOT}/vendor/bin/phpunit -c ${DRUPAL_CORE_PATH} "$@"
fi
```

### Creating phpunit.xml - step 1

My first thought was that if a `phpunit.xml` file doesn’t exist was to duplicate core’s `phpunit.xml.dist` file. However this isn’t enough to run the tests, as values such as `SIMPLETEST_BASE_URL`, `SIMPLETEST_DB` and `BROWSERTEST_OUTPUT_DIRECTORY` need to be populated.

As the tests wouldn't run at this point, I’ve exited early and displayed a message to the user to edit the new `phpunit.xml` file and run `fin phpunit` again.

```bash
if [ ! -e ${DRUPAL_CORE_PATH}/phpunit.xml ]; then
    echo "Copying ${DRUPAL_CORE_PATH}/phpunit.xml.dist to ${DRUPAL_CORE_PATH}/phpunit.xml."
    echo "Please edit it's values as needed and re-run 'fin phpunit'."
    cp ${DRUPAL_CORE_PATH}/phpunit.xml.dist ${DRUPAL_CORE_PATH}/phpunit.xml
    exit 1;
else
    ${PROJECT_ROOT}/vendor/bin/phpunit -c ${DRUPAL_CORE_PATH} "$@"
fi
```

However this isn’t as streamlined as I originally wanted as it still requires the user to perform an additional step before the tests can run.

### Creating phpunit.xml - step 2

My second idea was to keep a pre-configured file within the project repository, and to copy that into the expected location. That approach would mean that the project specific values would already be populated, as well as any customisations made to the default settings. I decided on `.docksal/drupal/core/phpunit.xml` to be the potential location.

Also, if this file is copied then we can go ahead and run the tests straight away rather than needing to exit early.

If a pre-configured file doesn’t exist, then we can default back to copying `phpunit.xml.dist`.

To avoid duplication, I created a reusable `run_tests()` function so it could be executed in either scenario.

```bash
run_tests() {
    ${PROJECT_ROOT}/vendor/bin/phpunit -c ${DRUPAL_CORE_PATH} "$@"
}

if [ ! -e ${DRUPAL_CORE_PATH}/phpunit.xml ]; then
    if [ -e "${PROJECT_ROOT}/.docksal/drupal/core/phpunit.xml" ]; then
        echo "Copying ${PROJECT_ROOT}/.docksal/drupal/core/phpunit.xml to ${DRUPAL_CORE_PATH}/phpunit.xml"
        cp "${PROJECT_ROOT}/.docksal/drupal/core/phpunit.xml" ${DRUPAL_CORE_PATH}/phpunit.xml
        run_tests "$@"
    else
        echo "Copying ${DRUPAL_CORE_PATH}/phpunit.xml.dist to ${DRUPAL_CORE_PATH}/phpunit.xml."
        echo "Please edit it's values as needed and re-run 'fin phpunit'."
        cp ${DRUPAL_CORE_PATH}/phpunit.xml.dist ${DRUPAL_CORE_PATH}/phpunit.xml
        exit 1;
    fi
else
    run_tests "$@"
fi
```

This means that I can execute less steps and run a much shorter command compared to the original, and even if someone didn’t have a `phpunit.xml` file created they could have copied into place and have tests running with only one command.

## The finished file

```bash
#!/usr/bin/env bash

#: exec_target = cli

## Run automated PHPUnit tests.
##
## Usage: fin phpunit <args>
##
## If a core/phpunit.xml file does not exist, one is copied from
## .docksal/core/phpunit.xml if that file exists, or copied from the default
## core/phpunit.xml.dist file.

DOCROOT_PATH="${PROJECT_ROOT}/${DOCROOT}"
DRUPAL_CORE_PATH="${DOCROOT_PATH}/core"

run_tests() {
    ${PROJECT_ROOT}/vendor/bin/phpunit -c ${DRUPAL_CORE_PATH} "$@"
}

if [ ! -e ${DRUPAL_CORE_PATH}/phpunit.xml ]; then
    if [ -e "${PROJECT_ROOT}/.docksal/drupal/core/phpunit.xml" ]; then
        echo "Copying ${PROJECT_ROOT}/.docksal/drupal/core/phpunit.xml to ${DRUPAL_CORE_PATH}/phpunit.xml"
        cp "${PROJECT_ROOT}/.docksal/drupal/core/phpunit.xml" ${DRUPAL_CORE_PATH}/phpunit.xml
        run_tests "$@"
    else
        echo "Copying phpunit.xml.dist to phpunit.xml"
        echo "Please edit it's values as needed and re-run 'fin phpunit'."
        cp ${DRUPAL_CORE_PATH}/phpunit.xml.dist ${DRUPAL_CORE_PATH}/phpunit.xml
        exit 0;
    fi
else
    run_tests "$@"
fi
```

It’s currently available as a [GitHub Gist][2], though I’m planning on moving it into a public GitHub repository either on my personal account or the [Microserve organisation][3], for people to either use as examples or to download and use directly.

I’ve also started to add other commands to projects such as `config-export` to standardise the way to export configuration from Drupal 8, run Drupal 7 tests with SimpleTest, and compile front-end assets like CSS within custom themes.

I think it’s a great way to shorten existing commands, or to group multiple commands into one like in this case, and I can see a lot of other potential uses for it during local development and continuous integration. Also being able to run one command like `fin init` and have it set up everything for your project is very convenient and a big time saver!

<div class="note" markdown="1">
Since writing this post, I’ve had a [pull request][8] accepted for this command to be added as a [Docksal add-on][9]. This means that the command can be added to any Docksal project by running `fin addon install phpunit`. It will be installed into the `.docksal/addons/phpunit` directory, and displayed under "Addons" rather than "Custom commands" when you run `fin`.
</div>

## Resources

- [PHPUnit](https://phpunit.de)
- [PHPUnit in Drupal 8][4]
- [Main Docksal website](https://docksal.io)
- [Docksal documentation](https://docksal.readthedocs.io)
- [Docksal: one tool to rule local and CI/CD environments][1] - Docksal talk from Drupaldelphia
- [phpcs example custom command][5]
- [phpunit command Gist][2]
- [Docksal addons blog post][9]
- [Docksal addons repository][10]

[0]: {{site.companies.microserve.url}}
[1]: https://youtu.be/1sjsvnx1P7g
[2]: https://gist.github.com/opdavies/72611f198ffd2da13f363ea65264b2a5
[3]: {{site.companies.microserve.github}}
[4]: https://www.drupal.org/docs/8/phpunit
[5]: https://github.com/docksal/docksal/blob/develop/examples/.docksal/commands/phpcs
[6]: /talks/tdd-test-driven-drupal
[7]: /articles/tags/testing
[8]: https://github.com/docksal/addons/pull/15
[9]: https://blog.docksal.io/installing-addons-in-a-docksal-project-172a6c2d8a5b
[10]: https://github.com/docksal/addons
