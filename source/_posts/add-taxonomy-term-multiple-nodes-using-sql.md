---
title: Add a Taxonomy Term to Multiple Nodes Using SQL
date: 2010-07-07
excerpt: How to add a new taxonomy term to multiple nodes in Drupal using SQL.
tags:
  - taxonomy
  - drupal-planet
  - drupal-6
  - sql
  - sequal-pro
  - database
---
In preparation for my Blog posts being added to [Drupal Planet](http://drupal.org/planet), I needed to create a new Taxonomy term (or, in this case, tag) called 'Drupal Planet', and assign it to new content to imported into their aggregator. After taking a quick look though my previous posts, I decided that 14 of my previous posts were relevant, and thought that it would be useful to also assign these the 'Drupal Planet' tag.

I didn't want to manually open each post and add the new tag, so I decided to make the changes myself directly into the database using SQL, and as a follow-up to a previous post - [Quickly Change the Content Type of Multiple Nodes using SQL](/blog/change-content-type-multiple-nodes-using-sql/).

**Again, before changing any values within the database, ensure that you have an up-to-date backup which you can restore if you encounter a problem!**

The first thing I did was create the 'Drupal Planet' term in my Tags vocabulary. I decided to do this via the administration area of my site, and not via the database. Then, using [Sequel Pro](http://www.sequelpro.com), I ran the following SQL query to give me a list of Blog posts on my site - showing just their titles and nid values.

```language-sql
SELECT title, nid FROM node WHERE TYPE = 'blog' ORDER BY title ASC;
```

I made a note of the nid's of the returned nodes, and kept them for later. I then ran a similar query against the term_data table. This returned a list of Taxonomy terms - showing the term's name, and it's unique tid value.

```language-sql
SELECT NAME, tid FROM term_data ORDER BY NAME ASC;
```

The term that I was interested in, Drupal Planet, had the tid of 84. To confirm that no nodes were already assigned a taxonomy term with this tid, I ran another query against the database. I'm using aliases within this query to link the node, term_node and term_data tables. For more information on SQL aliases, take a look at <http://w3schools.com/sql/sql_alias.asp>.

```language-sql
SELECT * FROM node n, term_data td, term_node tn WHERE td.tid = 84 AND n.nid = tn.nid AND tn.tid = td.tid;
```

As expected, it returned no rows.

The table that links node and term_data is called term_node, and is made up of the nid and vid columns from the node table, as well as the tid column from the term_data table. Is it is here that the additional rows would need to be entered.

To confirm everything, I ran a simple query against an old post. I know that the only taxonomy term associated with this post is 'Personal', which has a tid value of 44.

```language-sql
SELECT nid, tid FROM term_node WHERE nid = 216;
```

Once the query had confirmed the correct tid value, I began to write the SQL Insert statement that would be needed to add the new term to the required nodes. The nid and vid values were the same on each node, and the value of my taxonomy term would need to be 84.

Once this had completed with no errors, I returned to the administration area of my Drupal site to confirm whether or not the nodes had been assigned the new term.
