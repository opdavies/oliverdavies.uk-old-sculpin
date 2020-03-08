---
title: Writing a new Drupal 8 Module using Test Driven Development (TDD)
date: 2017-11-07
tags: [drupal, testing, tdd, simpletest, phpunit]
excerpt: How to write automated tests and follow test driven development for Drupal modules.
meta:
    image:
        url: /images/talks/test-driven-drupal-development.png
        width: 2560
        height: 1440
        type: image/png
promoted: true
---
<p class="text-center" markdown="1">![](/images/blog/drupalcamp-dublin.jpg)</p>

I recently gave a [talk on automated testing in Drupal][0] talk at [DrupalCamp Dublin][1] and as a lunch and learn session for my colleagues at Microserve. As part of the talk, I gave an example of how to build a Drupal 8 module using a test driven approach. I’ve released the [module code on GitHub][2], and this post outlines the steps of the process.

## Prerequisites

You have created a `core/phpunit.xml` file based on `core/phpunit.xml.dist`, and populated it with your database credentials so that PHPUnit can bootstrap the Drupal database as part of the tests. [Here is an example][5].

## Acceptance Criteria

For the module, we are going to satisfy this example acceptance criteria:

> As a site visitor,<br>
> I want to see all published pages at /pages<br>
> Ordered alphabetically by title

## Initial Setup

Let’s start by writing the minimal code needed in order for the new module to
be enabled. In Drupal 8, this is the `.info.yml` file.

```language-yaml
# tdd_dublin.info.yml

name: 'TDD Dublin'
excerpt: 'A demo module for DrupalCamp Dublin to show test driven module development.'
core: 8.x
type: module
```

We can also add the test file structure at this point too. We’ll call it `PageTestTest.php` and put it within a `tests/src/Functional` directory. As this is a functional test, it extends the `BrowserTestBase` class, and we need to ensure that the tdd_dublin module is enabled by adding it to the `$modules` array.

```language-php
// tests/src/Functional/PageListTest.php

namespace Drupal\Tests\tdd_dublin\Functional;

use Drupal\Tests\BrowserTestBase\BrowserTestBase;

class PageListTest extends BrowserTestBase {

  protected static $modules = ['tdd_dublin'];

}
```

With this in place, we can now start adding test methods.

## Ensure that the Listing page Exists

### Writing the First Test

Let’s start by testing that the listing page exists at /pages. We can do this by loading the page and checking the status code. If the page exists, the code will be 200, otherwise it will be 404.

I usually like to write comments first within the test method, just to outline the steps that I'm going to take and then replace it with code.

```language-php
public function testListingPageExists() {
  // Go to /pages and check that it is accessible by checking the status
  // code.
}
```

We can use the `drupalGet()` method to browse to the required path, i.e. `/pages`, and then write an assertion for the response code value.

```language-php
public function testListingPageExists() {
  $this->drupalGet('pages');
  
  $this->assertSession()->statusCodeEquals(200);
}
```

### Running the Test

In order to run the tests, you either need to include `-c core` or be inside the `core` directory when running the command, to ensure that the test classes are autoloaded so can be found, though the path to the `vendor` directory may be different depending on your project structure. You can also specify a path within which to run the tests - e.g. within the module’s `test` directory.

```language-plain
$ vendor/bin/phpunit -c core modules/custom/tdd_dublin/tests
```

<div class="note" markdown="1">
Note: I’m using Docksal, and I’ve noticed that I need to run the tests from within the CLI container. You can do this by running the `fin bash` command.
</div>

```language-plain
1) Drupal\Tests\tdd_dublin\Functional\PageListTest::testListingPageExists
Behat\Mink\Exception\ExpectationException: Current response status code is 404, but 200 expected.

FAILURES!
Tests: 1, Assertions: 1, Errors: 1.
```

Because the route does not yet exist, the response code returned is 404, so the test fails.

Now we can make it pass by adding the page. For this, I will use the Views module, though you could achieve the same result with a custom route and a Controller.

### Building the View

To begin with, I will create a view showing all types of content with a default sort order of newest first. We will use further tests to ensure that only the correct content is returned and that it is ordered correctly.

![](/images/blog/tdd-drupal-1.png) { .with-border }

The only addition I will make to the view is to add a path at `pages`, as per the acceptance criteria.

![](/images/blog/tdd-drupal-2.png) { .with-border }

### Exporting the View

With the first version of the view built, it needs to be incldued within the module so that it can be enabled when the test is run. To do this, we need to export the configuration for the view, and place it within the module’s `config/install` directory. This can be done using the `drush config-export` command or from within the Drupal UI. In either case, the `uid` line at the top of the file needs to be removed so the configuration can be installed.

Here is the exported view configuration:

```language-yaml
langcode: en
status: true
dependencies:
  module:
    - node
    - user
id: pages
label: pages
module: views
excerpt: ''
tag: ''
base_table: node_field_data
base_field: nid
core: 8.x
display:
  default:
    display_plugin: default
    id: default
    display_title: Master
    position: 0
    display_options:
      access:
        type: perm
        options:
          perm: 'access content'
      cache:
        type: tag
        options: {  }
      query:
        type: views_query
        options:
          disable_sql_rewrite: false
          distinct: false
          replica: false
          query_comment: ''
          query_tags: {  }
      exposed_form:
        type: basic
        options:
          submit_button: Apply
          reset_button: false
          reset_button_label: Reset
          exposed_sorts_label: 'Sort by'
          expose_sort_order: true
          sort_asc_label: Asc
          sort_desc_label: Desc
      pager:
        type: mini
        options:
          items_per_page: 10
          offset: 0
          id: 0
          total_pages: null
          expose:
            items_per_page: false
            items_per_page_label: 'Items per page'
            items_per_page_options: '5, 10, 25, 50'
            items_per_page_options_all: false
            items_per_page_options_all_label: '- All -'
            offset: false
            offset_label: Offset
          tags:
            previous: ‹‹
            next: ››
      style:
        type: default
        options:
          grouping: {  }
          row_class: ''
          default_row_class: true
          uses_fields: false
      row:
        type: fields
        options:
          inline: {  }
          separator: ''
          hide_empty: false
          default_field_elements: true
      fields:
        title:
          id: title
          table: node_field_data
          field: title
          entity_type: node
          entity_field: title
          label: ''
          alter:
            alter_text: false
            make_link: false
            absolute: false
            trim: false
            word_boundary: false
            ellipsis: false
            strip_tags: false
            html: false
          hide_empty: false
          empty_zero: false
          settings:
            link_to_entity: true
          plugin_id: field
          relationship: none
          group_type: group
          admin_label: ''
          exclude: false
          element_type: ''
          element_class: ''
          element_label_type: ''
          element_label_class: ''
          element_label_colon: true
          element_wrapper_type: ''
          element_wrapper_class: ''
          element_default_classes: true
          empty: ''
          hide_alter_empty: true
          click_sort_column: value
          type: string
          group_column: value
          group_columns: {  }
          group_rows: true
          delta_limit: 0
          delta_offset: 0
          delta_reversed: false
          delta_first_last: false
          multi_type: separator
          separator: ', '
          field_api_classes: false
      filters:
        status:
          value: '1'
          table: node_field_data
          field: status
          plugin_id: boolean
          entity_type: node
          entity_field: status
          id: status
          expose:
            operator: ''
          group: 1
      sorts:
        created:
          id: created
          table: node_field_data
          field: created
          order: DESC
          entity_type: node
          entity_field: created
          plugin_id: date
          relationship: none
          group_type: group
          admin_label: ''
          exposed: false
          expose:
            label: ''
          granularity: second
      header: {  }
      footer: {  }
      empty: {  }
      relationships: {  }
      arguments: {  }
      display_extenders: {  }
    cache_metadata:
      max-age: -1
      contexts:
        - 'languages:language_content'
        - 'languages:language_interface'
        - url.query_args
        - 'user.node_grants:view'
        - user.permissions
      tags: {  }
  page_1:
    display_plugin: page
    id: page_1
    display_title: Page
    position: 1
    display_options:
      display_extenders: {  }
      path: pages
    cache_metadata:
      max-age: -1
      contexts:
        - 'languages:language_content'
        - 'languages:language_interface'
        - url.query_args
        - 'user.node_grants:view'
        - user.permissions
      tags: {  }
```

When the test is run again, we see a different error that leads us to the next step.

```language-plain
1) Drupal\Tests\tdd_dublin\Functional\PageListTest::testListingPageExists
Drupal\Core\Config\UnmetDependenciesException: Configuration objects provided by <em class="placeholder">tdd_dublin</em> have unmet dependencies: <em class="placeholder">node.type.page (node), views.view.pages (node, views)</em>

FAILURES!
Tests: 1, Assertions: 0, Errors: 1.
```

This error is identifying unmet dependencies within the module’s configuration. In this case, the view that we’ve added depends on the node and views modules, but these aren’t enabled. To fix this, we can add the extra modules as dependencies of tdd_dublin so they will be enabled too.

```language-yaml
# tdd_dublin.info.yml

dependencies:
  - drupal:node
  - drupal:views
```

```language-plain
1) Drupal\Tests\tdd_dublin\Functional\PageListTest::testListingPageExists
Drupal\Core\Config\UnmetDependenciesException: Configuration objects provided by <em class="placeholder">tdd_dublin</em> have unmet dependencies: <em class="placeholder">views.view.pages (node.type.page)</em>

FAILURES!
Tests: 1, Assertions: 0, Errors: 1.
```

With the modules enabled, we can see one more unmet dependency for `node.type.page`. This means that we need a page content type to be able to install the view. We can fix this in the same way as before, by exporting the configuration and copying it into the `config/install` directory.

With this in place, the test should now pass - and it does.

```language-plain
Time: 26.04 seconds, Memory: 6.00MB

OK (1 test, 1 assertion)
```

We now have a test to ensure that the listing page exists.

## Ensure that only Published Pages are Shown

### Writing the Test

Now that we have a working page, we can now move on to checking that the correct content is returned. Again, I’ll start by writing comments and then translate that into code.

The objectives of this test are:

* To ensure that only page nodes are returned.
* To ensure that only published nodes are returned.

```language-php
public function testOnlyPublishedPagesAreShown() {
  // Given that a have a mixture of published and unpublished pages, as well
  // as other types of content.

  // When I view the page.

  // Then I should only see the published pages.
}
```

In order to test the different scenarios, I will create an additional "article" content type, create a node of this type as well as one published and one unpublished page. From this combination, I only expect one node to be visible.

```language-php
public function testOnlyPublishedPagesAreShown() {
  $this->drupalCreateContentType(['type' => 'article']);
  
  $this->drupalCreateNode(['type' => 'page', 'status' => TRUE]);
  $this->drupalCreateNode(['type' => 'article']);
  $this->drupalCreateNode(['type' => 'page', 'status' => FALSE]);

  // When I view the page.

  // Then I should only see the published pages.
}
```

We could use `drupalGet()` again to browse to the page and write assertions based on the rendered HTML, though I’d rather do this against the data returned from the view itself. This is so that the test isn’t too tightly coupled to the presentation logic, and we won’t be in a situation where at a later date the test fails because of changes made to how the data is displayed.

Rather, I’m going to use `views_get_view_result()` to programmatically get the result of the view. This returns an array of `Drupal\views\ResultRow` objects, which contain the nodes. I can use `array_column` to extract the node IDs from the view result into an array.

```language-php
public function testOnlyPublishedPagesAreShown() {
  $this->drupalCreateContentType(['type' => 'article']);
  
  $this->drupalCreateNode(['type' => 'page', 'status' => TRUE]);
  $this->drupalCreateNode(['type' => 'article']);
  $this->drupalCreateNode(['type' => 'page', 'status' => FALSE]);
  
  $result = views_get_view_result('pages');
  $nids = array_column($result, 'nid');
  
  // Then I should only see the published pages.
}
```

From the generated nodes, I can use `assertEquals()` to compare the returned node IDs from the view against an array of expected node IDs - in this case, I expect only node 1 to be returned.

```language-php
public function testOnlyPublishedPagesAreShown() {
  $this->drupalCreateContentType(['type' => 'article']);
  
  $this->drupalCreateNode(['type' => 'page', 'status' => TRUE]);
  $this->drupalCreateNode(['type' => 'article']);
  $this->drupalCreateNode(['type' => 'page', 'status' => FALSE]);
  
  $result = views_get_view_result('pages');
  $nids = array_column($result, 'nid');
  
  $this->assertEquals([1], $nids);
}
```

### Running the Test

The test fails as no extra conditions have been added to the view, though the default "Content: Published" filter is already excluding one of the page nodes. We can see from the output from the test that node 1 (a page) and node 2 (the article) are both being returned.

```language-plain
1) Drupal\Tests\tdd_dublin\Functional\PageListTest::testOnlyPublishedPagesAreShown
Failed asserting that two arrays are equal.
--- Expected
+++ Actual
@@ @@
 Array (
-    0 => 1
+    0 => '2'
+    1 => '1'
 )

FAILURES!
Tests: 1, Assertions: 3, Failures: 1.
```

### Updating the Test

We can fix this by adding another condition to the view, to only show content based on the node type - i.e. only return page nodes.

![](/images/blog/tdd-drupal-3.png) { .with-border }

Once the view is updated and the configuration is updated within the module, the test should then pass - and it does.

```language-plain
Time: 24.76 seconds, Memory: 6.00MB

OK (1 test, 3 assertions)
```

## Ensure that the Pages are in the Correct Order

### Writing the Test

As we know that the correct content is being returned, we can now focus on displaying it in the correct order. We’ll start again by adding a new test method and filling out the comments.

```language-php
public function testResultsAreOrderedAlphabetically() {
  // Given I have multiple nodes with different titles.

  // When I view the pages list.

  // Then I should see pages in the correct order.
}
```

To begin with this time, I’ll create a number of different nodes and specify the title for each. These are intentionally in the incorrect order alphabetically so that we can see the test fail initially and then see it pass after making a change so we know that the change worked.

```language-php
public function testResultsAreOrderedAlphabetically() {
  $this->drupalCreateNode(['title' => 'Page A']);
  $this->drupalCreateNode(['title' => 'Page D']);
  $this->drupalCreateNode(['title' => 'Page C']);
  $this->drupalCreateNode(['title' => 'Page B']);
 
  // When I view the pages list.
 
  // Then I should see pages in the correct order.
}
```

We can use the same method as the previous test to get the returned IDs, using `views_get_view_result()` and `array_column()`, and assert that the returned node IDs match the expected node IDs in the specified order. Based on the defined titles, the order should be 1, 4, 3, 2.

```language-php
public function testResultsAreOrderedAlphabetically() {
  $this->drupalCreateNode(['title' => 'Page A']);
  $this->drupalCreateNode(['title' => 'Page D']);
  $this->drupalCreateNode(['title' => 'Page C']);
  $this->drupalCreateNode(['title' => 'Page B']);
  
  $nids = array_column(views_get_view_result('pages'), 'nid');
  
  $this->assertEquals([1, 4, 3, 2], $nids);
}
```

### Running the Test

As expected the test fails, as the default sort criteria in the view orders the results by their created date.

In the test output, we can see the returned results are in sequential order so the results array does not match the expected one.

This would be particularly more complicated to test if I was using `drupalGet()` and having to parse the HTML, compared to getting the results as an array from the view programmatically.

```language-plain
1) Drupal\Tests\tdd_dublin\Functional\PageListTest::testResultsAreOrderedAlphabetically
Failed asserting that two arrays are equal.
--- Expected
+++ Actual
@@ @@
 Array (
-    0 => 1
-    1 => 4
-    2 => 3
-    3 => 2
+    0 => '1'
+    1 => '2'
+    2 => '3'
+    3 => '4'
 )

FAILURES!
Tests: 1, Assertions: 2, Failures: 1.
```

### Updating the Test

This can be fixed by removing the default sort criteria and adding a new one based on "Content: Title".

![](/images/blog/tdd-drupal-4.png) { .with-border }

Again, once the view has been updated and exported, the test should pass - and it does.

```language-plain
Time: 27.55 seconds, Memory: 6.00MB

OK (1 test, 2 assertions)
```

## Ensure all Tests Still Pass

Now we know that all the tests pass individually, all of the module tests should now be run to ensure that they all still pass and that there have been no regressions due to any of the changes.

```language-plain
docker@cli:/var/www$ vendor/bin/phpunit -c core modules/custom/tdd_dublin/tests

Testing modules/custom/tdd_dublin/tests
...

Time: 1.27 minutes, Memory: 6.00MB

OK (3 tests, 6 assertions)
```

They all pass, so we be confident that the code works as expected, we can continue to refactor if needed, and if any changes are made to this module at a later date, we have the tests to ensure that any regressions are caught and fixed before deployment.

## Next Steps

I’ve started looking into whether some of the tests can be rewritten as kernel tests, which should result in quicker test execution. I will post any updated code to the [GitHub repository][3], and will also do another blog post highlighting the differences between functional and kernel tests and the steps taken to do the conversion.

[0]: {{site.url}}/talks/tdd-test-driven-drupal
[1]: http://2017.drupal.ie
[2]: https://github.com/opdavies/tdd_dublin
[3]: https://packagist.org/packages/tightenco/collect
[4]: http://php.net/manual/en/function.array-column.php
[5]: https://gist.github.com/opdavies/dc5f0cea46ccd349b34a9f3a463c14bb
