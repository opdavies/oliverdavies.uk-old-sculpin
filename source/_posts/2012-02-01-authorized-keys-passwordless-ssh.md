---
title: How to use Authorized Keys to Create a Passwordless SSH Connection
slug: use-authorized-keys-create-passwordless-ssh-connection
tags:
  - linux
  - ssh
use: [posts]
redirect:
    - blog/authorized-keys-passwordless-ssh/
---
If you're accessing Linux servers or automating tasks between servers, rather than having to enter your user password every time, you can also use SSH public key authentication. This is a simple process that involves creating a local key and storing it within the *authorized_keys* file on the remote server.

1. Check if you already have a SSH key.
   `$ ssh-add -L` 
2. If you don't have one, create one.
   `$ ssh-keygen`
2. Upload the key onto the server. Replace *myserver* with the hostname or IP address of your remote server.
   `$ ssh-copy-id myserver`

If you're using Mac OS X and you don't have ssh-copy-id installed, download and install [Homebrew](http://mxcl.github.com/homebrew "Homebrew") and run the `brew install ssh-copy-id` command.

If successful, you should now see a message like:

> Now try logging into the machine, with "ssh 'myserver'", and check in:
>
>  ~/.ssh/authorized_keys
>
> to make sure we haven't added extra keys that you weren't expecting.

Now the next time that you SSH onto the server, it should log you in without prompting you for your password.
