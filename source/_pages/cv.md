---
type: page
title: Oliver Davies CV
roles:
    - company: inviqa
      title: Senior Software Engineer
      remote: true
      from: 2019-04
      to: ~
      description: |
          Initially working as part of a support team, maintaining and supporting existing Drupal 7 and 8 projects in an Agile environment. I’m currently working as part of a small project team, building the new Inviqa.com website on Drupal 8.

          I’m also part of the out-of-hours support team, supporting applications on a number of technologies and hosts for some of our largest clients.

    - company: microserve
      title: Senior Drupal Developer
      from: 2019-03
      to: 2019-04
      description: |
          Tasks included working on various Drupal 7 and 8 projects, adding new features and maintaining and supporting existing sites. I created custom modules for Drupal 7 and 8 with automated tests, including integrations with third-party services, and wrote migrations to import data from various sources into Drupal 8.

          My main project was leading the development of an events booking and management website, built with Drupal 8 and Drupal Commerce, during which I worked on site building tasks, module and theme development, server administration and data migration, as well as mentoring other team members.

    - company: appnovation
      title: Senior Drupal Developer
      from: 2016-05
      to: 2017-03
      description: |
          My main tasks were Drupal 7 and 8 site building, custom module development and theming for UK, US and Canadian clients.
          
    - company: cti
      title: Lead Drupal Developer
      remote: true
      from: 2016-05
      to: 2015-11
      description:
          Working on various Drupal 7 projects for clients including Greater London Authority, British Land and British Council, as well as various retainer contracts.

    - company: microserve
      title: Senior Drupal Developer
      from: 2015-07
      to: 2015-11
      description: |
          Full-stack Drupal 7 development, focussing on custom module development, REST server integration via restws module, and data migration from Drupal 6. This project also involved working on a non-Drupal PHP project, to which I added Composer to manage dependencies and Guzzle to perform HTTP requests to Drupal to trigger actions via REST.

    - company: drupal_association
      title: Drupal.org Developer
      remote: true
      from: 2014-05
      to: 2015-07
      description: |
          Working mainly on Drupal.org - fixing outstanding bugs from the Drupal 6 upgrade, as well as adding new features such as human-readable profile URLs, migrating legacy data from Drupal 6 into Drupal 7 fields, and improving the new user registration and role progression processes - committing changes to public Git repositories on Drupal.org itself.

          I worked on DrupalCon websites - making theme amends and fixing bugs on the websites for Austin and Latin America, and resolving payment gateway issues on events.drupal.org prior to DrupalCon Barcelona registrations.

          I also performed a functional and security review of jobs.drupal.org prior to it's public release, participated in testing and bug fixing of the responsive version of Bluecheese (the Drupal.org theme) which was launched in December 2014, and assisted in the upgrade of localize.drupal.org to Drupal 7 alongside high-profile community members.

    - company: nomensa
      title: Application Developer (PHP, Drupal) & System Administrator
      from: 2012-02
      to: 2013-04
      description: |
          I initially joined Nomensa as a contractor, focussing on custom module development for Drupal 7, including a custom integration with CiviCRM. After taking a permanent role, I continued with that project and also completed the theme development work - ensuring that it was WCAG 2.0 compliant. I also worked on various other projects, focussing on back-end development whilst working with and mentoring the front-end developers who worked on the theming.

          I worked alongside the in-house System Administrator - provisioning servers with a Nginx, PHP-FPM and MySQL stack, and deploying applications - as well as assisting with technical support and hardware issues within the office.

    - company: proctors
      title: PHP Developer
      from: 2011-04
      to: 2012-02
      description: |
          Development of new websites, mostly using Drupal and PHP, and providing ongoing support and maintenance of websites for existing clients. I developed the agency’s first Drupal 7 client project, and delivered a full build of a Drupal 6 project which included Ubercart for eCommerce (still in use in August 2019).

          I also provided in-person and remote user training for Drupal websites, and completed Linux server provisioning and configuration tasks for client websites.

    - company: horse_country
      title: Web Developer (PHP, Drupal)
      from: 2010-07
      to: 2011-04
      description: |
          Maintained and supported the company’s Drupal 6 website as part of a two-person team, re-architected and re-developed the Events section (including integrating Ubercart for paid events), made various theme improvements, and developed custom modules including the 'Now & Next' module to display the current and next programmes being shown on the channel.

          I also dealt with any issues that arose, including a SQL injection exploit of our OpenX ad server, and provided general IT support and assistance.
---

<div class="markup" markdown="1">
## About

An experienced full stack Developer, Acquia certified Drupal 8 Grand Master and Cloud Pro, with experience in DevOps/systems administration.

A passionate open source contributor and community leader who regularly attends, organises and speaks at user groups and conferences, and with a proven record of training and mentoring in both a work and community environment.

## Relevant Work Experience

{% for role in page.roles %}
{% set company = site.companies[role.company] %}
{% set title = role.title %}
{% if role.remote %}
    {% set title = "#{title} (Remote)" %}
{% endif %}

<article class="mt-6">
<span markdown="1">
### {{ title }}, {{ company.name }}
</span>

<div class="mt-3">
    {{ role.description|markdown }}
</div>
</article>
{% endfor %}

### Previous Work Experience

- **Software & Solutions Developer - Fujitsu Services Ltd** (February 2010 - July 2010)
- **Technical Support Specialist - Fujitsu Services Ltd** (July 2007 - February 2010)
- **Laptop Repair Technician** (Temporary) - Panasonic Computer Products Europe** (August 2006 - July 2007)
- **Laptop Repair Technician - Student Essentials Ltd** (September 2004 - August 2006)

## Open Source

### Drupal
I have been a member of the Drupal Community since 2008, and have contributed code to Drupal 7 and Drupal 8 core. I’ve also contributed to numerous projects on Drupal.org, and have written and maintain a number myself including the Override Node Options module which is used on ~30,000 sites, and a starter kit for building custom Drupal themes using Tailwind CSS.

### Symfony
I have previously contributed to the Symfony documentation, and plan to contribute more to Symfony in the future.

### Docksal (a local development environment for Drupal and PHP)
I have contributed code to the Docksal core project, and written and contributed addons for PHPUnit (Drupal 8) and SimpleTest (Drupal 7). I’ve written blog posts relating to Docksal, Drupal and automated testing.

### Drupal VM
I have had pull requests accepted to the Drupal VM project, and I wrote and open-sourced the Drupal VM CLI project - a command-line tool for downloading Drupal VM and generating bespoke configuration files based on user interaction. This was built on Symfony components and other PHP libraries including Twig and Guzzle.

### Sculpin
Sculpin is the static site generator that I use for my personal website, for which I have released the source code on GitHub, and have listed it on the Sculpin.io community page. I have had pull requests accepted to the Sculpin.io website and to the Pantheon Documentation website, I have written and released some custom Sculpin bundles, and I occasionally help triage the Sculpin issue queue on GitHub.

### WordPress
Having previously written the Nomensa Media Player module for Drupal, I fixed a bug in the equivalent WordPress plugin. I contacted the plugin’s author and assisted him in setting up and moving it into a GitHub repository, as well as submitting my patch as a pull request which was later approved.

### Ansible
I have written several Ansible roles for installing and configuring packages on CentOS/Red Hat and Debian/Ubuntu servers as well as locally on macOS.

### Tailwind CSS
I’ve contributed updates to the Tailwind CSS documentation. I’ve written and open-sourced some of my own Tailwind CSS plugins as well as a library of plugin testing helpers, and contributed to other plugins on GitHub.

### Hobbies and Interests

- Spending time with my family, in particular my wife and children.
- Learning and speaking Welsh.
- Training and competing in Brazilian Jiu-Jitsu. I was awarded by blue belt in May 2019.
- Learning new technologies (e.g. Symfony, Laravel, Vue.js).
- Attending and speaking at meetups and conferences, including PHP South Wales of which I am a co-organiser.
- Contributing to open source, and maintaining my own projects.
- Occasionally live coding on YouTube.
- Mentoring and assisting others.
</div>