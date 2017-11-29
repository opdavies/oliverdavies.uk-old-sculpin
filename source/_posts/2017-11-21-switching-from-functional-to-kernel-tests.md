---
title: Test Driven Drupal part 2 - Switching from Functional to Kernel Tests
slug: tdd-test-driven-drupal-2-kernel-tests
tags: [drupal, drupal-8, testing, tdd, phpunit]
use: [posts]
draft: true
---

## Functional Tests

Here is the original test class:

```language-php
namespace Drupal\Tests\tdd_dublin\Functional;

use Drupal\Tests\BrowserTestBase;

class PageListTest extends BrowserTestBase {

  protected static $modules = ['tdd_dublin'];

  public function testListingPageExists() {
    $this->drupalGet('pages');

    $this->assertSession()->statusCodeEquals(200);
  }

  public function testOnlyPublishedPagesAreShown() {
    $this->drupalCreateContentType(['type' => 'article']);

    $this->drupalCreateNode(['type' => 'page', 'status' => TRUE]);
    $this->drupalCreateNode(['type' => 'article']);
    $this->drupalCreateNode(['type' => 'page', 'status' => FALSE]);

    $nids = array_column(views_get_view_result('pages'), 'nid');

    $this->assertEquals([1], $nids);
  }

  public function testResultsAreOrderedAlphabetically() {
    $this->drupalCreateNode(['title' => 'Page A']);
    $this->drupalCreateNode(['title' => 'Page D']);
    $this->drupalCreateNode(['title' => 'Page C']);
    $this->drupalCreateNode(['title' => 'Page B']);

    $nids = array_column(views_get_view_result('pages'), 'nid');

    $this->assertEquals([1, 4, 3, 2], $nids);
  }

}
```

These pass, but take 1.74 minutes to run.

```language-plain
docker@cli:/var/www$ vendor/bin/phpunit -c core modules/custom/tdd_dublin/tests/src
PHPUnit 4.8.36 by Sebastian Bergmann and contributors.

Testing modules/custom/tdd_dublin/tests/src
...

Time: 1.74 minutes, Memory: 6.00MB

OK (3 tests, 6 assertions)
```

## Kernel Test

Add a new class at `tests/src/Kernel/PageListTest.php` and extend the `Drupal\KernelTests\KernelTestBase` base class.

```language-php
<?php

namespace Drupal\Tests\tdd_dublin\Kernel;

use Drupal\KernelTests\KernelTestBase;

class PageListTest extends KernelTestBase {
}
```

```language-php
public function testOnlyPublishedPagesAreShown() {
  $result = views_get_view_result('pages');
  $nids = array_column($result, 'nid');

  $this->assertEquals([1], $nids);
}
```

```language-plain
There was 1 error:

1) Drupal\Tests\tdd_dublin\Kernel\PageListTest::testOnlyPublishedPagesAreShown
Error: Call to undefined function Drupal\Tests\tdd_dublin\Kernel\views_get_view_result()
```

```language-php
protected static $modules = ['views'];
```

```language-plain
There was 1 failure:

1) Drupal\Tests\tdd_dublin\Kernel\PageListTest::testOnlyPublishedPagesAreShown
Failed asserting that two arrays are equal.
--- Expected
+++ Actual
@@ @@
 Array (
-    0 => 1
 )
```

haven't installed config

```language-php
protected function setUp() {
  parent::setUp();

  $this->installConfig(['tdd_dublin']);
}
```

```language-plain
There was 1 error:

1) Drupal\Tests\tdd_dublin\Kernel\PageListTest::testOnlyPublishedPagesAreShown
LogicException: tdd_dublin module is not enabled.
```

```language-php
protected static $modules = ['tdd_dublin', 'views'];
```

```language-plain
There was 1 error:

1) Drupal\Tests\tdd_dublin\Kernel\PageListTest::testOnlyPublishedPagesAreShown
Drupal\Core\Config\Schema\SchemaIncompleteException: No schema for node.type.page
```

```language-php
protected static $modules = ['node', 'tdd_dublin', 'views'];
```

```language-plain
There was 1 error:

1) Drupal\Tests\tdd_dublin\Kernel\PageListTest::testOnlyPublishedPagesAreShown
Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException: The service "drupal.proxy_original_service.node_preview" has a dependency on a non-existent service "user.private_tempstore".
```

```language-php
protected static $modules = [
  'node',
  'tdd_dublin',
  'user',
  'views',
];
```

```language-plain
There was 1 error:

1) Drupal\Tests\tdd_dublin\Kernel\PageListTest::testResultsAreOrderedAlphabetically
Use of undefined constant DRUPAL_OPTIONAL - assumed 'DRUPAL_OPTIONAL'
```

```language-php
protected static $modules = [
  'node',
  'system',
  'tdd_dublin',
  'user',
  'views'
];
```

```language-plain
There was 1 error:

1) Drupal\Tests\tdd_dublin\Kernel\PageListTest::testOnlyPublishedPagesAreShown
Drupal\Core\Database\DatabaseExceptionWrapper: Exception in pages[pages]: SQLSTATE[42S02]: Base table or view not found: 1146 Table 'default.test46585659node_field_data' doesn't exist: SELECT node_field_data.title AS node_field_data_title, node_field_data.nid AS nid
FROM
{node_field_data} node_field_data
...
```

```language-php
protected function setUp() {
  parent::setUp();

  $this->installConfig(['tdd_dublin']);

  $this->installEntitySchema('node');
}
```

```
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;

public function testOnlyPublishedPagesAreShown() {
  NodeType::create(['type' => 'article', 'name' => t('Article')]);

  Node::create($this->getValidParams(['status' => TRUE]))->save();
  Node::create($this->getValidParams(['type' => 'article']))->save();
  Node::create($this->getValidParams(['status' => FALSE]))->save();

  $nids = array_column(views_get_view_result('pages'), 'nid');

  $this->assertEquals([1], $nids);
}

private function getValidParams(array $overrides = []) {
  return array_merge([
    'status' => TRUE,
    'title' => $this->randomString(),
    'type' => 'page',
  ], $overrides);
}
```

```language-plain
Caused by
PDOException: SQLSTATE[42S02]: Base table or view not found: 1146 Table 'default.test36008020users' doesn't exist
```

```language-php
protected function setUp() {
  parent::setUp();

  $this->installConfig(['tdd_dublin']);

  $this->installEntitySchema('node');
  $this->installEntitySchema('user');
}
```

```
Testing modules/custom/tdd_dublin/tests/src/Kernel
.

Time: 8.9 seconds, Memory: 6.00MB

OK (1 test, 4 assertions)
```

## testing result order

```language-php
public function testResultsAreOrderedAlphabetically() {
}
```

```language-php
public function testResultsAreOrderedAlphabetically() {
  $nids = array_column(views_get_view_result('pages'), 'nid');

  $this->assertEquals([1, 4, 3, 2], $nids);
}
```

```language-plain
There was 1 failure:

1) Drupal\Tests\tdd_dublin\Kernel\PageListTest::testResultsAreOrderedAlphabetically
Failed asserting that two arrays are equal.
--- Expected
+++ Actual
@@ @@
 Array (
-    0 => 1
-    1 => 4
-    2 => 3
-    3 => 2
 )
```

```language-php
public function testResultsAreOrderedAlphabetically() {
  Node::create($this->getValidParams(['title' => 'Page A']))->save();
  Node::create($this->getValidParams(['title' => 'Page D']))->save();
  Node::create($this->getValidParams(['title' => 'Page C']))->save();
  Node::create($this->getValidParams(['title' => 'Page B']))->save();

  $nids = array_column(views_get_view_result('pages'), 'nid');

  $this->assertEquals([1, 4, 3, 2], $nids);
}
```

```language-plain
Testing modules/custom/tdd_dublin/tests/src/Kernel
.

Time: 8.5 seconds, Memory: 6.00MB

OK (1 test, 4 assertions)
```
