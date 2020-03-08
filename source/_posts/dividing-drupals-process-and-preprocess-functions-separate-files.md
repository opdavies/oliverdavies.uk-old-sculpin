---
title: Dividing Drupal's process and preprocess functions into separate files
date: 2012-05-24
excerpt: If you use a lot of process and preprocess functions within your Drupal theme, then your template.php can get very long and it can become difficult to find a certain piece of code. Following the example of the Omega theme, I've started separating my process and preprocess functions into their own files.
tags:
  - drupal
  - code
  - theming
  - preprocessing
---
If you use a lot of process and preprocess functions within your Drupal theme, then your template.php can get very long and it can become difficult to find a certain piece of code. Following the example of the [Omega theme](http://drupal.org/project/omega "The Omega theme on Drupal.org"), I've started separating my process and preprocess functions into their own files. For example, mytheme_preprocess_node can be placed within a preprocess/node.inc file, and mytheme_process_page can be placed within process/page.inc.

The first step is to use the default mytheme_process() and mytheme_preprocess() functions to utilise my custom function. So within my template.php file:

```language-php
<?php

/**
 * Implements hook_preprocess().
 * 
 * Initialises the mytheme_invoke() function for the preprocess hook.
 */
function mytheme_preprocess(&$variables, $hook) {
  mytheme_invoke('preprocess', $hook, $variables);
}

/**
 * Implements hook_process().
 * 
 * Initialises the mytheme_invoke() function for the process hook.
 */
function mytheme_process(&$variables, $hook) {
  mytheme_invoke('process', $hook, $variables);
}
```

Now, to write the `mytheme_invoke()` function:

```language-php
<?php

/**
 * Invokes custom process and preprocess functions.
 *
 * @param string $type
 *   The type of function we are trying to include (i.e. process or preprocess).
 *
 * @param array $variables
 *   The variables array.
 *
 * @param string $hook
 *   The name of the hook.
 *   
 * @see mytheme_preprocess()   
 * @see mytheme_process()
 */
function mytheme_invoke($type, $hook, &$variables) {
  global $theme_key;
  
  // The name of the function to look for (e.g. mytheme_process_node).
  $function = $theme_key . '_' . $type . '_' . $hook;

  // If the function doesn't exist within template.php, look for the 
  // appropriate include file.
  if (!function_exists($function)) {
    // The file to search for (e.g. process/node.inc).
    $file = drupal_get_path('theme', $theme_key) . '/' . $type . '/' . $type . '-' . str_replace('_', '-', $hook) . '.inc';

    // If the file exists, include it.
    if (is_file($file)) {
      include($file);
    }
  }

  // Try to call the function again.
  if (function_exists($function)) {
    $function($variables);
  }
}
```

As `mytheme_invoke()` checks to see if the function already exists before searching for checking the include files, I could still add the functions into template.php as normal and this would override any corresponding include file.
