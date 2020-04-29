---
layout: default
technologies:
    backend: [PHP (5-7), MySQL, PHPUnit, Behat]
    frontend: [HTML, Twig, CSS, Sass/Less, Tailwind CSS, Vue.js, Alpine.js, Jest]
    frameworks: [Drupal, WordPress, Symfony, Laravel, Sculpin, Jekyll]
    other: [Git, GitHub, Linux, Vagrant, Docker, Fabric, Ansible, Puppet, Apache, Nginx, Jenkins]
---
<div class="markup mx-auto px-4 max-w-2xl text-lg" markdown="1">
# About me

<img class="mt-8 border border-gray-400" src="/images/drupalcamp-north.jpeg">

{% set company = site.companies[site.work.company] %}
Hi, I’m Oliver. I'm a web developer, systems administrator, open source project creator and maintainer, community organiser, speaker and mentor based in Wales, UK.

I currently work for [{{company.name}}][company_url] as a {{ site.work.role }}. In my spare time, I'm sometimes available for freelance development or systems administration work.

I specialise in working with the Drupal content management system, Symfony framework, Linux operating systems and Ansible for server provisioning and application deployments.

I started learning and using Drupal in 2007, was previously employed by the [Drupal Association][drupal_association] to work on drupal.org, and am an Acquia certified Drupal 8 Grand Master.

I’m a big advocate for automated testing and test driven development, and am currently [writing a book](/book) on automated testing with Drupal.

[company_url]: {{company.url}}
[drupal_association]: {{site.companies.drupal_association.url}}

## Technologies

- **Back-end:** {{ page.technologies.backend|join(', ') }}
- **Front-end:** {{ page.technologies.frontend|join(', ') }}
- **Frameworks/CMSes:** {{ page.technologies.frameworks|join(', ') }}
- **Other:** {{ page.technologies.other|join(', ') }}

## Open source
My Drupal related projects can be found on [Drupal.org][drupalorg]. Everything else is on [GitHub][github].

[drupalorg]: {{site.drupalorg.url_new}}
[github]: {{site.github.url}}

## Community
I'm an organiser of the [PHP South Wales user group][php_south_wales]. We hold monthly meetups in Cardiff including talk nights, coding sessions, workshops and socials.

 I was previously the organiser of the Drupal Bristol and South Wales Drupal user groups, and a co-organiser of the PHP South West user group and the DrupalCamp Bristol conference.
 
 [php_south_wales]: https://phpsouthwales.uk

## Speaking
I also occasionally do live streams of coding conference talks. These can be found on my [YouTube channel][youtube].

[youtube]: {{site.youtube.channel.url}}

## Contact me
</div>
