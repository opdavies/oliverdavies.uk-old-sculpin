---
title: Useful Vagrant Commands
date: 2013-11-27
excerpt: Here are the basic commands that you need to adminster a virtual machine using <a href="http://vagrantup.com" title="The Vagrant Home page">Vagrant</a>.
tags:
  - vagrant
---
[Vagrant](http://www.vagrantup.com "About Vagrant") is a tool for managing virtual machines within [VirtualBox](https://www.virtualbox.org) from the command line. Here are some useful commands to know when using Vagrant.

Command | Description
:-|:-
vagrant init {box} | Initialise a new VM in the current working directory. Specify a box name, or "base" will be used by default.
vagrant status | Shows the status of the Vagrant box(es) within the current working directory tree.
vagrant up (--provision) | Boots the Vagrant box. Including "–provision" also runs the "vagrant provision" command.
vagrant reload (--provision) | Reloads the Vagrant box. Including "--provision" also runs the "vagrant provision" command.
vagrant provision | Provision the Vagrant box using Puppet.
vagrant suspend | Suspend the Vagrant box. Use "vagrant up" to start the box again.
vagrant halt (-f) | Halt the Vagrant box. Use -f to forcefully shut down the box without prompting for confirmation.
vagrant destroy (-f) | Destroys a Vagrant box. Use -f to forcefully shut down the box without prompting for confirmation.

The full Vagrant documentation can be found at <http://docs.vagrantup.com/v2/>.
