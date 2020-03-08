---
title: "Drupal 8 Commerce: Fixing 'No Such Customer' error on checkout"
date: 2018-08-15
excerpt: Fixing a Drupal Commerce error when a user tries to complete a checkout.
tags:
    - drupal
    - drupal-8
    - drupal-commerce
    - stripe
---
Recently I was experiencing an issue on the Drupal 8 website I’m working on, where a small number of users were not able to complete the checkout process and instead got a generic `The site has encountered an unexpected error` message.

Looking at the log, I was able to see the error being thrown (the customer ID has been redacted):

> Stripe\Error\InvalidRequest: No such customer: cus_xxxxxxxxxxxxxx in Stripe\ApiRequestor::_specificAPIError() (line 124 of /var/www/vendor/stripe/stripe-php/lib/ApiRequestor.php).

Logging in to the Stripe account, I was able to confirm that the specified customer ID did not exist. So where was it coming from, and why was Drupal trying to retrieve a non-existent customer?

## Investigation

After some investigation, I found a table in the database named `user__commerce_remote_id` which stores the remote customer ID for each payment method (again, the customer ID has been redacted).

![A screenshot of a row in the user__commerce_remote_id table](/images/blog/commerce-stripe-error/remote-id-table.png){.border.p-1}

The `entity_id` and `revision_id` values in this case refer to the user that the Stripe customer has been associated with.

As there was no customer in Stripe with this ID, I think that this must be a customer ID from the test environment (the data from which was deleted before the site went live).

### Drupal code

This I believe is the Drupal code where the error was being triggered:

```language-php
// modules/contrib/commerce_stripe/src/Plugin/Commerce/PaymentGateway/Stripe.php

public function createPayment(PaymentInterface $payment, $capture = TRUE) {
  ...

  $owner = $payment_method->getOwner();
  if ($owner && $owner->isAuthenticated()) {
    $transaction_data['customer'] = $this->getRemoteCustomerId($owner);
  }

  try {
    $result = \Stripe\Charge::create($transaction_data);
    ErrorHelper::handleErrors($result);
  }
  catch (\Stripe\Error\Base $e) {
    ErrorHelper::handleException($e);
  }

  ...
}
```

### Stripe code

I can also see in the Stripe library where the original error is generated.

```language-php
private static function _specificAPIError($rbody, $rcode, $rheaders, $resp, $errorData)
{
    $msg = isset($errorData['message']) ? $errorData['message'] : null;
    $param = isset($errorData['param']) ? $errorData['param'] : null;
    $code = isset($errorData['code']) ? $errorData['code'] : null;

    switch ($rcode) {
        ...

        case 404:
            return new Error\InvalidRequest($msg, $param, $rcode, $rbody, $resp, $rheaders);

        ...
    }
}
```

## Solution

After confirming that it was the correct user ID, simply removing that row from the database allowed the new Stripe customer to be created and for the user to check out successfully.
