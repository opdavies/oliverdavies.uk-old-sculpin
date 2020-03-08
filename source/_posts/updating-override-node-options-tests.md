---
title: Updating Override Node Options Tests
date: 2017-05-05
excerpt: ~
tags:
    - drupal
    - drupal-modules
    - drupal-planet
    - testing
draft: true
---
Recently, I reviewed [a patch][1] in the [Override Node Options][2] module issue queue. For those not familiar with it, the module adds extra permissions for node options like "authored by" and "published on" which are normally only available to users with the `administer nodes` permission. What the patch does is to optionally add another set of permissions that enable options for all content types - e.g. "override published option for all node types", in addition to or instead of the content type specific ones.

It was quite an old issue and the latest patch needed to be re-rolled due to merge conflicts, but the existing tests still passed. Though as no new tests were added for the new functionality, these needed to be added before I committed it.

## Reviewing the Existing Tests

The first thing to do was to run the existing tests and check that they still passed. I do this on the command line by typing `php scripts/run-tests.sh --class OverrideNodeOptionsTestCase`.

```language-markup
Drupal test run
---------------

Tests to be run:
 - Override node options (OverrideNodeOptionsTestCase)

Test run started:
 Saturday, April 29, 2017 - 14:44

Test summary
------------

Override node options 142 passes, 0 fails, 0 exceptions, and 38 debug messages

Test run duration: 32 sec
```

After confirming that the existing tests still passed, I reviewed them to see what could be re-used.

This is one of the original tests:

```language-php
/**
 * Test the 'Authoring information' fieldset.
 */
protected function testNodeOptions() {
  $this->adminUser = $this->drupalCreateUser(array(
    'create page content',
    'edit any page content',
    'override page published option',
    'override page promote to front page option',
    'override page sticky option',
    'override page comment setting option',
  ));
  $this->drupalLogin($this->adminUser);

  $fields = array(
    'status' => (bool) !$this->node->status,
    'promote' => (bool) !$this->node->promote,
    'sticky' => (bool) !$this->node->sticky,
    'comment' => COMMENT_NODE_OPEN,
  );
  $this->drupalPost('node/' . $this->node->nid . '/edit', $fields, t('Save'));
  $this->assertNodeFieldsUpdated($this->node, $fields);

  $this->drupalLogin($this->normalUser);
  $this->assertNodeFieldsNoAccess($this->node, array_keys($fields));
}
```

The first part of the test is creating and logging in a user with some content type specific override permissions (`$this->adminUser`), and then testing that the fields were updated when the node is saved. The second part is testing that the fields are not visible for a normal user without the extra permissions (`$this->normalUser`), which is created in the `setUp()` class' method.

To test the new "all types" permissions, I created another user to test against called `$generalUser` and run the first part of the tests in a loop.

## Beginning to Refactor the Tests

With the tests passing, I was able to start refactoring.

```language-php
// Create a new user with content type specific permissions.
$specificUser = $this->drupalCreateUser(array(
  'create page content',
  'edit any page content',
  'override page published option',
  'override page promote to front page option',
  'override page sticky option',
  'override page comment setting option',
));

foreach (array($specificUser) as $account) {
  $this->drupalLogin($account);
  
  // Test all the things.
  ...
}
```

I started with a small change, renaming `$this->adminUser` to `$specificUser` to make it clearer what permissions it had, and moving the tests into a loop so that the tests can be repeated for both users.

After that change, I ran the tests again to check that everything still worked.

## Adding Failing Tests

The next step is to start testing the new permissions. 

```language-php
...

$generalUser = $this->drupalCreateUser(array());

foreach (array($specificUser, $generalUser) as $account) {
  $this->drupalLogin($account);

  // Test all the things.
}
```

I added a new `$generalUser` to test the general permissions and added to the loop, but in order to see the tests failing intially I assigned it no permissions. When running the tests again, 6 tests have failed.

```language-markup
Test summary
------------

Override node options 183 passes, 6 fails, 0 exceptions, and 49 debug messages

Test run duration: 28 sec
```

Then it was a case of re-adding more permissions to the user and seeing the number of failures decrease, confirming that the functionality was working correctly.

TODO: Add another example.

## Gotchas

There was a bug that I found where a permission was added, but wasn't used within the implementation code. After initially expecting the test to pass after adding the permission to `$generalUser` and the test still failed, I noticed that the 

This was fixed by adding the extra code into `override_node_options.module`.

```language-diff
- $form['comment_settings']['#access'] |= user_access('override ' . $node->type . ' comment setting option');
+ $form['comment_settings']['#access'] |= user_access('override ' . $node->type . ' comment setting option') || user_access('override all comment setting option');
```

The other issue that I found was within `testNodeRevisions`. `assertNodeFieldsUpdated()` was failing after being put in a loop as the `vid` was not the same as what was expected.

Note: You can get more verbose output from `run-tests.sh` by adding the `--verbose` option.

> Node vid was updated to '3', expected 2.

```language-diff
- $fields = array(
-   'revision' => TRUE,
- );
- $this->drupalPost('node/' . $this->node->nid . '/edit', $fields, t('Save'));
- $this->assertNodeFieldsUpdated($this->node, array('vid' => $this->node->vid + 1));
+ $generalUser = $this->drupalCreateUser(array(
+   'create page content',
+   'edit any page content',
+   'override all revision option',
+ ));
+
+ foreach (array($specificUser, $generalUser) as $account) {
+   $this->drupalLogin($account);
+
+   // Ensure that we have the latest node data.
+   $node = node_load($this->node->nid, NULL, TRUE);
+
+   $fields = array(
+     'revision' => TRUE,
+   );
+   $this->drupalPost('node/' . $node->nid . '/edit', $fields, t('Save'));
+   $this->assertNodeFieldsUpdated($node, array('vid' => $node->vid + 1));
+ }
```

The crucial part of this change was the addition of `$node = node_load($this->node->nid, NULL, TRUE);` to ensure that the latest version of the node was loaded during each loop.

## Conclusion

- Ensure that the existing tests were passing before starting to refactor.
- Start with small changes and continue to run the tests to ensure that nothing has broken.
- After the first change, I committed it as `WIP: Refactoring tests`, and used `git commit --amend --no-edit` to amend that commit each time I had refactored another test. After the last refactor, I updated the commit message.
- It’s important to see tests failing before making them pass. This was achieved by initially assigning no permissions to `$generalUser` so that the fails failed and then added permissions and re-run the tests to ensure that the failure count decreased with each new permission.

With the refactoring complete, the number of passing tests increased from 142 to 213.

```language-markup
Override node options 213 passes, 0 fails, 0 exceptions, and 60 debug messages

Test run duration: 25 sec
```

<img src="/images/blog/override-node-options-refactor-tests-new-passing.png" alt="">

[Here][3] are my full changes from the previous patch, where I added the new tests as well as some small refactors.

[1]: https://www.drupal.org/node/974730
[2]: https://www.drupal.org/project/override_node_options
[3]: https://www.drupal.org/files/issues/interdiff_25712.txt
