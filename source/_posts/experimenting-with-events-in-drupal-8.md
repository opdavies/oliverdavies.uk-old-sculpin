---
title: Experimenting with events in Drupal 8
date: 2018-08-21
excerpt: Trying a different way of structuring Drupal modules, using event subscribers and autowiring.
tags:
    - drupal
    - drupal-8
    - drupal-planet
    - php
    - symfony
promoted: true
---
I’ve been experimenting with moving some code to Drupal 8, and I’m quite intrigued by a different way that I’ve tried to structure it - using event subscribers, building on some of the takeaways from Drupal Dev Days.

Here is how this module is currently structured:

![](/images/blog/events-drupal-8/1.png){.border .p-1}

Note that there is no `opdavies_blog.module` file, and rather than calling actions from within a hook like `opdavies_blog_entity_update()`, each action becomes it’s own event subscriber class.

This means that there are no long `hook_entity_update` functions, and instead there are descriptive, readable event subscriber class names, simpler action code that is responsibile only for performing one task, and you’re able to inject and autowire dependencies into the event subscriber classes as services - making it easier and cleaner to use dependency injection, and simpler write tests to mock dependencies when needed.

The additional events are provided by the [Hook Event Dispatcher module](https://www.drupal.org/project/hook_event_dispatcher).

## Code

`opdavies_blog.services.yml`:

```yaml
services:
  Drupal\opdavies_blog\EventSubscriber\PostToMedium:
    autowire: true
    tags:
      - { name: event_subscriber }

  Drupal\opdavies_blog\EventSubscriber\SendTweet:
    autowire: true
    tags:
      - { name: event_subscriber }
```

<div class="note" markdown="1">
Adding `autowire: true` is not required for the event subscriber to work. I’m using it to automatically inject any dependencies into the class rather than specifying them separately as arguments.
</div>

`src/EventSubscriber/SendTweet.php`:

```php
namespace Drupal\opdavies_blog\EventSubscriber;

use Drupal\hook_event_dispatcher\Event\Entity\EntityUpdateEvent;
use Drupal\hook_event_dispatcher\HookEventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SendTweet implements EventSubscriberInterface {

  ...

  public static function getSubscribedEvents() {
    return [
      HookEventDispatcherInterface::ENTITY_UPDATE => 'sendTweet',
    ];
  }

  public function sendTweet(EntityUpdateEvent $event) {
    // Perform checks and send the tweet.
  }

}
```
