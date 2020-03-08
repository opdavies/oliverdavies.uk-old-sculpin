---
title: My Sublime Text 2 settings
date: 2012-10-25
excerpt: <a href="http://www.sublimetext.com/2" title="Sublime Text 2">Sublime Text 2</a> has been my text editor of choice for the past few months, and I use it at home, in work, and on any virtual machines that I run. So rather than having to manually re-enter my settings each time, I thought that I'd document them here for future reference.
tags:
  - sublime-text
---
[Sublime Text 2](http://www.sublimetext.com/2) has been my text editor of choice for the past few months, and I use it at home, in work, and on any virtual machines that I run. So rather than having to manually re-enter my settings each time, I thought that I'd document them here for future reference.

These preferences ensure that the code is compliant with [Drupal coding standards](http://drupal.org/coding-standards "Drupal coding standards on Drupal.org") - using two spaces instead of a tab, no trailing whitespace, blank line at the end of a file etc.

## Preferences

These can be changed by going to Preferences > Settings - User.

```language-json
{
  "color_scheme": "Packages/Theme - Aqua/Color Schemes/Tomorrow Night Aqua.tmTheme",
  "default_line_ending": "unix",
  "ensure_newline_at_eof_on_save": true,
  "fallback_encoding": "UTF-8",
  "file_exclude_patterns":
  [
    "*.pyc",
    "*.pyo",
    "*.exe",
    "*.dll",
    "*.obj",
    "*.o",
    "*.a",
    "*.lib",
    "*.so",
    "*.dylib",
    "*.ncb",
    "*.sdf",
    "*.suo",
    "*.pdb",
    "*.idb",
    ".DS_Store",
    "*.class",
    "*.psd",
    "*.db",
    "*.sublime*"
  ],
  "folder_exclude_patterns":
  [
    ".svn",
    ".git",
    ".hg",
    "CVS",
    "FirePHPCore"
  ],
  "font_options":
  [
    "no_bold",
    "no_italic"
  ],
  "font_size": 16.0,
  "highlight_line": true,
  "ignored_packages":
  [
  ],
  "line_padding_bottom": 1,
  "rulers":
  [
    80
  ],
  "save_on_focus_lost": true,
  "shift_tab_unindent": true,
  "tab_size": 2,
  "theme": "Soda Light.sublime-theme",
  "translate_tabs_to_spaces": true,
  "trim_automatic_white_space": true,
  "trim_trailing_white_space_on_save": true,
  "word_wrap": false
}
```

## Key bindings

These can be changed by going to Preferences > Key Bindings - User.

```language-json
[
  { "keys": ["alt+s"], "command": "toggle_side_bar" },
  { "keys": ["alt+r"], "command": "reindent" }
]
```

## Packages

These are the packages that I currently have installed.

* [DocBlockr](https://github.com/spadgos/sublime-jsdocs "DocBlockr on GitHub")
* [Drupal API](https://github.com/BrianGilbert/Sublime-Text-2-Goto-Drupal-API)
* [LESS](https://github.com/danro/LESS-sublime)
* [Package Control](http://wbond.net/sublime_packages/package_control)
* [Sublime CodeIntel](http://github.com/Kronuz/SublimeCodeIntel)
* [Theme - Soda](https://github.com/buymeasoda/soda-theme)
