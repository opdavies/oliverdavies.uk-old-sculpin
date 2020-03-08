---
title: Queuing Private Messages in Drupal 8
date: 2018-02-27
excerpt: Introducing the Private Message Queue module for Drupal 8.
tags:
    - drupal
    - drupal-8
    - drupal-modules
    - drupal-planet
    - open-source
---
My current project at [Microserve][0] is a Drupal 8 website that uses the [Private Message][1] module for users to send messages to each other.

In some cases though, the threads could contain hundreds of recipients so I decided that it would be good to queue the message requests so that they can be processed as part of a background process for better performance. The Private Message module does not include this, so I've written and released a separate [Private Message Queue][2] module.

## Queuing a Message

The module provices a `PrivateMessageQueuer` service (`private_message_queue.queuer`) which queues the items via the `queue()` method.

The method accepts an array of `User` objects as the messsage recipients, the message body text and another user as the message owner. (I’m currently considering [whether to make the owner optional][4], and default to the current user if one is not specified)

Here is an example:

```php
$recipients = $this->getRecipients(); // An array of User objects.
$message = 'Some message text';
$owner = \Drupal::currentUser();

$queuer = \Drupal::service('private_message_queue.queuer');
$queuer->queue($recipients, $message, $owner);
```

These three pieces of data are then saved as part of the queued item. You can see these by checking the "queue" table in the database or by running `drush queue-list`.

![](/images/blog/private-message-queue.png)

```
$ drush queue-list
Queue                  Items  Class
private_message_queue  19     Drupal\Core\Queue\DatabaseQueue
```

## Processing the Queue

The module also provides a `PrivateMessageQueue` queue worker, which processes the queued items. For each item, it creates a new private message setting the owner and the message body.

It uses the `PrivateMessageThread` class from the Private Message module to find for an existing thread for the specified recipients, or creates a new thread if one isn't found. The new message is then added to the thread.

The queue is processed on each cron run, so I recommend adding a module like [Ultimate Cron][3] so that you can process the queued items frequently (e.g. every 15 minutes) and run the heavier tasks like checking for updates etc less frequently (e.g. once a day).

You can also process the queue manually with Drush using the `drush queue-run <queue-name>` command - e.g. `drush queue-run private_message_queue`.

```
$ drush queue-run private_message_queue
Processed 19 items from the private_message_queue queue in 3.34 sec.
```

[0]: {{site.companies.microserve.url}}
[1]: https://www.drupal.org/project/private_message
[2]: https://www.drupal.org/project/private_message_queue
[3]: https://www.drupal.org/project/ultimate_cron
[4]: https://www.drupal.org/project/private_message_queue/issues/2948233
