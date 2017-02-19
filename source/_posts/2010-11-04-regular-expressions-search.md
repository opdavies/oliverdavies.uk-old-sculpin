---
title: Use Regular Expressions to Search and Replace in Coda or TextMate
slug: use-regular-expressions-search-and-replace-coda-or-textmate
tags:
  - taxonomy
  - sequel-pro
  - database
  - coda
  - regular-expression
  - textmate
use: [posts]
redirect:
    - blog/regular-expressions-search/
---
As in [the original post](/blog/add-taxonomy-term-multiple-nodes-using-sql/ "Quickly adding a taxonomy term to multiple nodes using SQL"), I'd generated a list of node ID values, and needed to add structure the SQL update statment formatted in a certain way. However, I changed my inital query slightly to out put the same nid value twice.

~~~sql
SELECT nid, nid FROM node WHERE TYPE = 'blog' ORDER BY nid ASC;
~~~

Then, I could select all of the returned rows, copy the values, and paste them into Coda:

As before, I needed my SQL update statement to be in the following format:

~~~sql
INSERT INTO term_node VALUE (nid, vid, tid), (nid2, vid2, tid);
~~~

As I mentioned previously, the nid and vid values are the same for each node, and the tid will remain constant. In this case, the tid value that I needed to use was '63'.

So, using the 'Find and Replace' function within Coda, combined with [regular expressions](http://en.wikipedia.org/wiki/Regular_expression) (regex), I can easily format the values as needed. To begin with, I need to ensure that the RegEx search option is enabled, and that I'm using the correct escape character.

The first thing that I wanted to do was add the seperating comma between the two values. To do this, I 
perform a search for `\s*\t`. This searches for everything that is whitespace AND is a tab value. I can then add the comma as the replacement for each result.

All 31 lines have been changed.

Next, I can use `\n` to target the lines between the rows. I'll replace it with the next comma, the number 63 (the tid value), the closing bracket, another comma, re-add the line and add the opening bracket.

The only two lines that aren't changed are the first and last, as they don't have any line breaks following them. I can complete these lines manually. Now all I need to do is add the beginning of the SQL update statement, then copy and paste it into Sequel Pro.
