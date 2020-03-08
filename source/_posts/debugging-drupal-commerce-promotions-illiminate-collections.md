---
title: Debugging Drupal Commerce Promotions and Adjustments using Illuminate Collections (Drupal 8)
date: 2018-10-24
excerpt: Using Laravel’s Illuminate Collections to debug an issue with a Drupal Commerce promotion.
tags:
  - drupal
  - drupal-8
  - drupal-commerce
  - drupal-planet
  - illuminate-collections
  - laravel-collections
  - php
promoted: true
---
Today I found another instance where I decided to use [Illuminate Collections][0] within my Drupal 8 code; whilst I was debugging an issue where a [Drupal Commerce][1] promotion was incorrectly being applied to an order.

No adjustments were showing in the Drupal UI for that order, so after some initial investigation and finding that `$order->getAdjustments()` was empty, I determined that I would need to get the adjustments from each order item within the order.

If the order were an array, this is how it would be structured in this situation:

```php
$order = [
  'id' => 1,
  'items' => [
    [
      'id' => 1,
      'adjustments' => [
        ['name' => 'Adjustment 1'], 
        ['name' => 'Adjustment 2'], 
        ['name' => 'Adjustment 3'],
      ]
    ],
    [
      'id' => 2,
      'adjustments' => [
        ['name' => 'Adjustment 4'],
      ]
    ],
    [
      'id' => 3,
      'adjustments' => [
        ['name' => 'Adjustment 5'], 
        ['name' => 'Adjustment 6'],
      ]
    ],
  ],
];
```

## Getting the order items

I started by using `$order->getItems()` to load the order’s items, converted them into a Collection, and used the Collection’s `pipe()` method and the `dump()` function provided by the [Devel module][2] to output the order items.

```php
collect($order->getItems())
  ->pipe(function (Collection $collection) {
    dump($collection);
  });
```

## Get the order item adjustments

Now we have a Collection of order items, for each item we need to get it’s adjustments. We can do this with `map()`, then call `getAdjustments()` on the order item.

This would return a Collection of arrays, with each array containing it’s own adjustments, so we can use `flatten()` to collapse all the adjustments into one single-dimensional array.

```php
collect($order->getItems())
  ->map(function (OrderItem $order_item) {
    return $order_item->getAdjustments();
  })
  ->flatten(1);
```

There are a couple of refactors that we can do here though:

- Use `flatMap()` to combine the `flatten()` and `map()` methods.
- Use [higher order messages][3] to delegate straight to the `getAdjustments()` method on the order, rather than having to create a closure and call the method within it.

```php
collect($order->getItems())
  ->flatMap->getAdjustments();
```

## Filtering

In this scenario, each order item had three adjustments - the correct promotion, the incorrect one and the standard VAT addition.
I wasn’t concerned about the VAT adjustment for debugging, so I used `filter()` to remove it based on the result of the adjustment’s `getSourceId()` method.

```php
collect($order->getItems())
  ->flatMap->getAdjustments()
  ->filter(function (Adjustment $adjustment) {
    return $adjustment->getSourceId() != 'vat';
  });
```

## Conclusion

Now I have just the relevant adjustments, I want to be able to load each one to load it and check it’s conditions. To do this, I need just the source IDs.

Again, I can use a higher order message to directly call `getSourceId()` on the adjustment and return it’s value to `map()`.

```php
collect($order->getItems())
  ->flatMap->getAdjustments()
  ->filter(function (Adjustment $adjustment) {
    return $adjustment->getSourceId() != 'vat';
  })
  ->map->getSourceId();
```

This returns a Collection containing just the relevant promotion IDs being applied to the order that I can use for debugging.

Now just to find out why the incorrect promotion was applying!

[0]: https://laravel.com/docs/collections
[1]: https://drupalcommerce.org
[2]: https://www.drupal.org/project/devel
[3]: https://laravel-news.com/higher-order-messaging
