---
title: How to fix Vagrant Loading the Wrong Virtual Machine
slug: fix-vagrant-loading-wrong-virtual-machine
tags:
  - vagrant
  - virtualbox
meta:
  description: How to fix it when Vagrant loads the wrong virtual machine.
use: [posts]
redirect:
    - blog/fix-vagrant-loading-wrong-virtual-machine/
---
{% block excerpt %}
A few times recently, I've had instances where [Vagrant](https://www.vagrantup.com) seems to have forgotten which virtual machine it's supposed to load, probably due to renaming a project directory or the .vagrant directory being moved accidentally.

Here are the steps that I took to fix this and point Vagrant back at the correct VM.
{% endblock %}

{% block content %}
A few times recently, I've had instances where [Vagrant](https://www.vagrantup.com) seems to have forgotten which virtual machine it's supposed to load, probably due to renaming a project directory or the .vagrant directory being moved accidentally.

Here are the steps that I took to fix this and point Vagrant back at the correct VM.

1. Stop the machine from running using the `$ vagrant halt` command.
2. Use the `$ VBoxManage list vms` command to view a list of the virtual machines on your system. Note the ID of the correct VM that should be loading. For example, `"foo_default_1405481857614_74478" {e492bfc3-cac2-4cde-a396-e81e37e421e2}`. The number within the curly brackets is the ID of the virtual machine.
3. Within the .vagrant directory in your project (it is hidden by default), update the ID within the machines/default/virtualbox/id file.
4. Start the new VM with `$ vagrant up`.
{% endblock %}
