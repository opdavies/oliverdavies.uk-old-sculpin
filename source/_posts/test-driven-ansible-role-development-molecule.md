---
title: Test Driven Ansible Role Development with Molecule
date: 2019-06-02
excerpt: Some resources that I found for testing Ansible roles with a tool called Molecule.
tags: [ansible, molecule, testing, video]
---
I used to maintain a number of [Ansible roles][roles], and I recently wrote one for automatically generating `settings.php` files for Drupal projects that I use for some client projects as part of the [Ansible and Ansistrano deployment process][talk], as it can populate these files with credentials stored in Ansible Vault.

I uploaded an initial version of the role [onto GitHub][github], but haven’t yet released it onto Ansible Galaxy.

I’d seen in other people’s roles and read elsewhere about writing automated tests for Ansible roles using a tool called [Molecule][molecule], and wanted to write some tests for this role before publishing it onto Galaxy.

I looked around for resources about Molecule, and found a [blog post by Jeff Geerling][jeff-post], but also this YouTube video that I found very helpful.

I’ve since been re-writing the role from scratch based on Molecule, and plan to release an official version of it soon.

{% include 'video-embed' with {
    classes: 'video-full',
    video: {
        id: 'DAnMyBZ8-Qs',
        attr: {
            height: '315',
            width: '560',
        }
    }
} %}

[github]: https://github.com/opdavies/ansible-role-drupal-settings
[jeff-post]: https://www.jeffgeerling.com/blog/2018/testing-your-ansible-roles-molecule
[molecule]: https://molecule.readthedocs.io
[roles]: https://docs.ansible.com/ansible/latest/user_guide/playbooks_reuse_roles.html
[talk]: /talks/deploying-php-ansible-ansistrano
