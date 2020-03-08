---
title: Creating and using custom tokens in Drupal 7
date: 2013-02-16
excerpt: This post outlines the steps required to create your own custom tokens in Drupal.
tags:
  - drupal
  - drupal-planet
  - drupal-7
  - tokens
---
This post outlines the steps required to create your own custom tokens in Drupal.

When writing the recent releases of the [Copyright Block](http://drupal.org/project/copyright_block) module, I used tokens to allow the user to edit and customise their copyright message and place the copyright_message:dates token in the desired position. When the block is rendered, the token is replaced by the necessary dates.

We will be using the fictional *foo* module to demonstrate this.

## Requirements

* [Token module](http://drupal.org/project/token)

## Recommended

* [Devel module](http://drupal.org/project/devel) - useful to run `dpm()` and `kpr()` functions
* [Copyright Block module](http://drupal.org/project/copyright_block) - 7.x-2.x and 6.x-1.x use tokens, handy as a reference

## Implementing hook_token_info()

The first thing that we need to do is define the new token type and/or the token itself, along with it's descriptive text. To view the existing tokens and types, use `dpm(token_get_info());`, assuming that you have the [Devel module](http://drupal.org/project/devel) installed.

```language-php
/**
 * Implements hook_token_info().
 */
function foo_token_info() {
  $info = array();

  // Add any new tokens.
  $info['tokens']['foo']['bar'] = t('This is my new bar token within the foo type.');

  // Return them.
  return $info;
}
```

In this case, the token called *bar* resides within the *foo* group.

If I needed to add a new token within an existing token type, such as 'node', the syntax would be `$info['tokens']['node']['bar']`.

## Implementing hook_tokens()

Now that the Token module is aware of our new token, we now need to determine what the token is replaced with. This is done using `hook_tokens()`. Here is the basic code needed for an implementation:

```language-php
/**
 * Implements hook_tokens().
 */
function foo_tokens($type, $tokens, array $data = array(), array $options = array()) {
  $replacements = array();

  // Code goes here...

  // Return the replacements.
  return $replacements;
}
```

The first thing to check for is the type of token using an `if()` function, as this could be an existing type like 'node', 'user' or 'site', or a custom token type like 'foo'. Once we're sure that we're looking at the right type(s), we can use `foreach ($tokens as $name => $original)` to loop through each of the available tokens using a `switch()`. For each token, you can perform some logic to work out the replacement text and then add it into the replacements array using `$replacements[$original] = $new;`.

```language-php
/**
 * Implements hook_tokens().
 */
function foo_tokens($type, $tokens, array $data = array(), array $options = array()) {
  $replacements = array();

  // The first thing that we're going to check for is the type of token - node,
  // user etc...
  if ($type == 'foo') {
    // Loop through each of the available tokens.
    foreach ($tokens as $name => $original) {
      // Find the desired token by name
      switch ($name) {
        case 'bar':
          $new = '';

          // Work out the value of $new...

          // Add the new value into the replacements array.
          $replacements[$original] = $new;
          break;
      }
    }
  }

  // Return the replacements.
  return $replacements;
}
```

## Example

An example from Copyright Block module:

```language-php
/**
 * Implements hook_tokens().
 */
function copyright_block_tokens($type, $tokens, array $data = array(), array $options = array()) {
  $replacements = array();

  if ($type == 'copyright_statement') {
    foreach ($tokens as $name => $original) {
      switch ($name) {
        case 'dates':
          $start_year = variable_get('copyright_block_start_year', date('Y'));
          $current_year = date('Y');

          $replacements[$original] = $start_year < $current_year ? $start_year . '-' . $current_year : $start_year;
          break;
      }
    }
  }

  return $replacements;
}
```

## Using token_replace()

With everything defined, all that we now need to do is pass some text through the `token_replace()` function to replace it with the values defined within `hook_token()`.

```language-php
$a = t('Something');
// This would use any token type - node, user etc.
$b = token_replace($a);
// This would only use foo tokens.
$c = token_replace($a, array('foo'));
```
