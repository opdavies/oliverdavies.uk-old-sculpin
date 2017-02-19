---
title: How to Install and Configure Subversion (SVN) Server on Ubuntu
slug: install-and-configure-subversion-svn-server-ubuntu
tags:
  - svn
  - ubuntu
  - version-control
use: [posts]
redirect:
    - blog/install-and-configure-subversion-svn-server-ubuntu/
---
Recently, I needed to set up a Subversion (SVN) server on a Ubuntu Linux server. This post is going to outline the steps taken, and the commands used, to install and configure the service.

Note: As I was using Ubuntu, I was using the 'apt-get' command to download and install the software packages. If you're using a different distribution of Linux, then this command may be different. I'm also assuming that Apache is already installed.

Firstly, I'm going to ensure that all of my installed packages are up to date, and install any available updates.

    $ sudo apt-get update

Now, I need to download the subversion, subversion-tools and libapache2 packages.

    $ sudo apt-get install subversion subversion-tools libapache2-svn

These are all of the packages that are needed to run a Subversion server.

## Create subversion directory

Now, I need to create the directory where my repositories are going to sit. I've chosen this directory as I know that it's one that is accessible to my managed backup service.

    $ sudo mkdir /home/svn

## Create a test repository

First, I'll create a new folder in which I'll create my test project, and then I'll create a repository for it.

    $ sudo mkdir ~/test
    $ sudo svnadmin create /home/svn/test -m 'initial project structure'

This will create a new repository containing the base file structure.

## Adding files into the test project

    $ cd ~/test 
    $ mkdir trunk tags branches

I can now import these new directories into the test repository.

    $ sudo svn import ~/test file:///home/svn/test -m 'Initial project directories'

This both adds and commits these new directories into the repository.

In order for Apache to access the SVN repositories, the `/home/svn` directory needs to be owned by the same user and group that Apache runs as. In Ubuntu, this is usually www-data. To change the owner of a directory, use the chown command.

    $ sudo chown -R www-data:www-data /home/svn

## Configuring Apache

The first thing that I need to do is enable the dav_svn Apache module, using the a2enmod command.

    $ sudo a2enmod dav_svn

With this enabled, now I need to modify the Apache configuration file.

    $ cd /etc/apache2
    $ sudo nano apache2.conf

At the bottom of the file, add the following lines, and then save the file by pressing Ctrl+X.

~~~
<Location "/svn">
  DAV svn
  SVNParentPath /home/svn
</Location>
~~~

With this saved, restart the Apache service for the changes to be applied.

    sudo service apache2 restart

I can now browse through my test repository by opening Firefox, and navigating to `http://127.0.0.1/svn/test`. Here, I can now see my three directories, although they are currently all empty.

<img class="imagecache-blog imagecache image-caption caption" title="Viewing the test repository in Firefox" src="http://oliverdavies.co.uk/sites/default/files/imagecache/blog/images/blog/how-install-and-configure-subversion-svn-server-ubuntu/viewing-test-repository-firefox.png" alt="Image: Screenshot of the test SVN repository in Firefox">

## Securing my SVN repositories

Before I start committing any files to the test repository, I want to ensure that only authorised users can view it - currently anyone can view the repository and it's contents, as well as being able to checkout and commit files. To do this, I'm going to require the user to enter a username and a password before viewing or performing any actions with the repository.

Re-open apache2.conf, and replace the SVN Location information with this:

~~~~
<Location "/svn">
  DAV svn
  SVNParentPath /home/svn
  AuthType Basic
  AuthName "My SVN Repositories"
  AuthUserFile /etc/svn-auth
  Require valid-user
</Location>
~~~~

Now I need to create the password file.

    $ htpasswd -cm /etc/svn-auth oliver

I'm prompted to enter and confirm my password, and then my details are saved. The Apache service will need to be restarted again, and then the user will need to authenticate themselves before viewing the repositories.

<img class="imagecache-blog imagecache image-caption caption" title="Being prompted for authorisation" src="http://oliverdavies.co.uk/sites/default/files/imagecache/blog/images/blog/how-install-and-configure-subversion-svn-server-ubuntu/being-prompted-authorisation_0.png" alt="Image: Screenshot of the authorisation prompt when opening the repository">

## Checking out the repository and commiting files

For example, now want to checkout the files within my repository into a new directory called 'test2' within my home directory. Firstly, I need to create the new directory, and then I can issue the checkout command.

    $ cd ~
    $ mkdir test2
    $ svn checkout http://127.0.0.1/svn/test/trunk test2

I'm passing the command two arguments - the first is the URL of the repository's trunk directory, and the second is the directory where the files are to be placed. As no files have been commited yet into the trunk, it appears to be empty - but if you perform an ls -la command, you'll see that there is a hidden .svn directory.

Now you can start adding files into the directory. Once you've created your files, perform a svn add command, passing in individual filenames as further arguments.

    $ svn add index.php
    $ svn add *

With all the required files added, they can be committed using `svn commit -m 'commit message'` command, and the server can be updated using the svn up command.
