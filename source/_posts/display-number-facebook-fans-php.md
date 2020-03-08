---
title: Display the Number of Facebook fans in PHP
date: 2011-03-15
excerpt: How to use PHP to display the number of fans of a Facebook page.
tags:
  - php
---
Replace the $page_id value with your Page ID number (unless you want to show the number of fans for this site).You can find your Page ID by logging into your Facebook account, going to 'Adverts and Pages', clicking 'Edit page', and looking at the URL.

For example, mine is <https://www.facebook.com/pages/edit/?id=143394365692197&amp;sk=basic>.

I've also wrapped the output in a number_format() function so that it properly formatted with commas etc - like where I've used it within the [Gold Event listing](http://www.horseandcountry.tv/events/paid) on the Horse & Country TV website.

```language-php
$page_id = "143394365692197";
$xml = @simplexml_load_file("http://api.facebook.com/restserver.php?method=facebook.fql.query&amp;query=SELECT%20fan_count%20FROM%20page%20WHERE%20page_id=".$page_id."") or die ("a lot");
$fans = $xml->page->fan_count;
print number_format($fans);
```

This code was originally found at <http://wp-snippets.com/display-number-facebook-fans>.
