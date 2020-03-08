---
title: Live Blogging From SymfonyLive London 2019
date: 2019-09-13
tags:
    - conference
    - symfony
    - symfonylive
    - php
---
Inspired by [Matt Stauffer](https://twitter.com/stauffermatt)'s [live blogging of the keynote](https://mattstauffer.com/blog/introducing-laravel-vapor) at Laracon US, I’m going to do the same for the sessions that I’m attending at [SymfonyLive London 2019](https://london2019.live.symfony.com)...

## Keynote (Back to the basics)

**Embrace the Linux philosophy**

* How we grow the Symfony ecosystem. Built abstracts.
* HttpFoundation, HttpKernel
* Moved to infrastructure
* A few abstractions on top of PHP. Improved versions of PHP functions (`dump` and `var_dump`)
* Started a add higher level abstractions (e.g. Mailer), built on the lower ones.
* Recently worked on PHPUnit assertions. Mailer in Symony 4.4. Can test if an email is sent or queued

**Building flexible high-level abstractions on top of low-level ones**

### What's next?

* Mailer announced in London last year. New component.
* System emails? e.g. new customer, new invoice.
* Symfony Mailer = Built-in responsive, flexible, and generic system emails
    - Twig with TwigExtraBundle
    - Twig `inky-extra` package (Twig 1.12+)
    - Zurb Foundation for Emails CSS stylesheet
    - Twig `cssinliner-extra` package (Twig 1.12+)
    - Optimised Twig layouts
* `SystemEmail` class extends templated email
* Can set importance, 
* Customisable
* Always trying to keep flexible, so things can be overidden and customised

### Sending SMS messages

* new `Texter` and `SmsMessage` class for sending SMS messages
* Same abstraction as emails, but for SMS messages
* Based on HttpClient + Symfony Messenger and third-party providers (Twilio and Nexmo) `twilio://` and `nemxo://`
* Can set via transport `$sms->setTransport('nexmo')`
* Extend the `SystemEmail` and do what you want
* Failover

### Sending Messages

* Create `ChatMessage`
* Telegram and Slack
* `$message->setTransport('telegram')`, `$bus->dispatch($message)`
* Send to Slack **and** Telegram
* `SlackOptions` and `TelegramOptions` for adding emojis etc
* Common transport layer `TransportInterface`, `MessageInterface`
* Failover - e.g. if Twilio is down, send to Telegram

### New component - SymfonyNotifier

* Channels - email, SMS, chat
* Transport, slack, telegram, twilio
* Create a notification, arguments are message and transports (array)
* Receiver
* Customise notifications, `InvoiceNotification` extends `Notification`. `getChannels`
    * Override default rendering
    * `ChatNotificationInterface` - `asChatMessage()`
* Semantic configuration
    * `composer req twilio-notifier telegram-notifier`
* Channels
    - Mailer
    - Chatter
    - Texter
    - Browser
    - Pusher (iOS, Android, Desktop native notifications)
    - Database (web notification centre)
    - **A unified way to notify Users via a unified Transport layer**
* Each integration is only 40 lines of code

### What about a SystemNotification?

* Autoconfigured channels
* `new SystemNotification`, `Notifier::getSystemReceivers`
* Importance, automatically configures channels
* Different channels based on importance
* `ExceptionNotification` - get email with stack trace attached

Notifier
* send messages via a unified api
* send to one or many receivers
* Default configu or custom one


### How can we leverage this new infrastructure?

* `Monolog NotifierHandler` - triggered on `Error` level logs
* Uses notified channel configuration
* Converts Error level logs to importance levels
* Configurablelike other Notifications
* 40 lines of code
* Failed Messages Listener - 10 lines of glue code

* **Experimental component in 5.0**
* Can't in in 4.4 as it's a LTS version
* First time an experimental component is added
* Stable in 5.1

## Queues, busses and the Messenger component (Tobias Nyholm)

* Stack is top and buttom - Last-in, first-out
* Queue is back and front - last in, first out

### 2013

* Using Symfony, used 40 or 50 bundles in a project - too much information!
* Used to copy and paste, duplicate a lot of code
* Testing your controllers - controllers as services?
* Controllers are 'comfortable'
* Tried adding `CurrentUserProvider` service to core, should be passed as an argument. Cannot test.
* 'Having Symfony all over the place wasn't the best thing' - when to framework (Matthias Noback)
    - Hexagonal architecture
    - Keep your kernel away from infrastructure. Let the framework handle the infrastructure.
* Controller -> Command -> Command Bus -> `CommandHandler`

#### What did we win?

* Can leverage Middleware with a command bus
* Queues as a service (RabbitMQ)
* Work queue - one producer, multiple consumers
* Queues should be durable - messages are also stored on disk, consumers should acknowledge a message once a message is handled
* Publish/subscribe
    - Producer -> Fanout/direct with routing (multiple queues) -> multiple consumers
* Topics - wildcards

### 2016

* New intern. Understand everything, 'just PHP'. Plain PHP application, not 'scary Symfony'

### Symfony Messenger

* `composer req symfony/messager` - best MessageBus implementation
* Message -> Message bus -> Message handler
* Message is a plain PHP class
* Handler is a normal PHP class which is invokable
* `messenger:message_hander` tag in config
* Autowire with `MessageHandlerInterface`
* What if it takes 20 seconds to send a message? Use asynchronous.
* Transports as middleware (needs sender, receiver, configurable with DSN, encode/decode). `MESSENGER_DSN` added to `.env`
* Start consumer with `bin/console messager:consume-messages`. Time limit with `--time-limit 300`
* PHP Enqueue - production ready, battle-tested messaging solution for PHP

### Issues

* Transformers, takes an object and transforms into an array - `FooTransformer implements TransformerInterface`.
* Don't break other apps by changing the payload.

#### Multiple buses

* Command bus, query bus, event bus
* Separate actions from reactions

#### Envelope

* Stamps for metadata - has the item been on the queue already?

#### Failures

* Requeue, different queue or same queue after a period of time
* Failed queue 1 every minute, failed queue 2 every hour - temporary glitches or a bug?

#### Creating entities

* What if two users registered at the same tiem? Use uuids rather than IDs.
* Symfony validation - can be used on messages, not just forms.

* Cache everything
    - Option 1: HTTP request -> Thin app (gets responses from Redis) -> POST to queue. Every GET request would warm cache
    - Option 2: HTTP request -> Thin app -> return 200 response -> pass to workers

* Tip: put Command and CommandHandlers in the same directory

## HttpClient (Nicolas Grekas)

* new symfony component, released in may
* Httpclient contracts, separate package that contains interfaces
    * Symfony
    * PHP-FIG
    * Httplug
* `HttpClient::create()`. `$client->get()`
* JSON decoded with error handling
* Used on symfony.com website (#1391). Replaces Guzzle `Client` for `HttpClientInterface`
* Object is stateless, Guzzle is not. Doesn't handle cookies, cookies are state
* Remove boilerplate - use `toArray()`
* Options as third argument - array of headers, similar to Guzzle

### What can we do with the Response?
* `getStatusCode(): int`
* `getHeaders(): array`
* `getContent(): string`
* `toArray(): array`
* `cancel(): void`
* `getInfo(): array` - metadata
* Everything is lazy!
* 80% of use-cases covered

### What about PSR-18?
* Decorator/adapter to change to PSR compatible
* Same for Httplug

### What about the remaining 20%?
* Options are part of the abstraction, not the implementation

#### Some of the options

* `timeout` - control inactivity periods
* `proxy` - get through a http proxy
* `on_progress` - display a progress bar / build a scoped client
* `base_url` - resolve relative URLS / build a scoped client
* `resolve` - protect webhooks against calls to internal endpoints
* `max_redirects` - disable or limit redirects

* Robust and failsafe by default

* Streamable uploads - `$mimeParts->toIterable()`.
* donwload a file

    ```php
    foreach ($client->stream($response) as $chunk) {
        // ... 
    }
    ```

- Responses are lazy, requests are concurrent
- Asychronus requests. Reading in network order
```
foreach ($client->stream($responses) as $response => $chunk) {
    if ($chunk->isLast()) {
        // a $response completed
    } else {
        // a $response's got network activity or timeout
    }
}
```

* 379 request completed in 0.4s!
* `Stream` has second argument, max number of seconds to wait before yielding a timeout chunk
* `ResponseInterface::getInfo()` - get response headers, redirect count and URL, start time, HTTP method and code, user data and URL
    * `getInfo('debug')` - displays debug information

### The components

* `NativeHttpClient` and `CurlHttpClient`
     - both provide
         + 100% contracts
         + secure directs
         + extended (time) info
         + transparent HTTP compression and (de)chunking
         + automatic HTTP proxy configuration via env vars
#### `NativeHttpClient`
- is most portable, works for everyone
- based on HTTP stream wrapper with fixed redirect logic
- blocking until response headers arrive

#### `CurlHttpClient`
-  Requires ext-curl with fixed redirection logic
-  Multiplexing response headers and bodies
-  Leverages HTTP/2 and PUSH when available
- Keeps connections open also between synchronous requests, no DNS resolution so things are faster

#### Decorators

- ScopingHttpClient - auto-configure options based on request URL
- MockHttpClient - for testing, doesn't make actual HTTP requests
- CachingHttpClient - adds caching on a HTTP request
- Psr18Client
- HttplugClient
- TraceableHttpClient

### Combining
#### FrameworkBundle/Autowiring

```yml
framework:
    http_client:
        max_host_connections: 4
        deault_options:
            # ....
    scoped_client:
        # ...
```
#### HttpBrowser

* HttpClient + DomCrawler + CssSelector + HttpKernel + BrowserKit
* RIP Goutte!

### Coming in 4.4...

- `max_duration`
- `buffer` based on a callable
- `$chunk->isInformational()`
- `$response->toStream()`
- Async-compatible extensibility, when decoration is not enough

`composer req symfony/http-client`

## Symfony Checker is coming (Valentine Boineau)

* Static analysis tool for Symfony
    - Does a method exist?
    - Is it deprecated?
* insight.symfony.com
* @symfonyinsight
* Released soon

### Differences
* Specialise in Symfony - can see more relevant things
* Different interface to other services

## Feeling unfulfilled by SPA promises? Go back to Twig (Dan Blows)

A way on the front-end JS, CSS, images at the beginning of the request, sends a HTTP request (XHR/AJAX) to the back-end

### Why SPAs?
- A way on the front-end JS, CSS, images at the beginning of the request, sends a HTTP request (XHR/AJAX) to the back-end
- no full page refresh
- Supposed to be much quicker
- 'Right tool for the job' - JS on the front-end, PHP on the back-end
- Division of responsibility == faster development
- Reusable API - Api -> Mobile App and SPA - easy to add another consumer
- Easier to debug?

### Why not SPAs?
- Lots of HTTP requests (400 to load the initial page on one project) == slow front end
- Blurred responsibilities == tightly coupled teams
- harder to debug, bugs fall between systems and teams. Huge gap between front-end and back-end, passing responsibilites.
- You can fix these problems in SPAs, but is it worth it?
    + Examples of good SPAs - Trello, Flickr

### Using Twig as an alternative to an SPA?

#### Faster UI - Try and figure out where the problem is.

If you're trying to speed things up, find out where the problem is.

* Browser tools
* Web Debug Toolbar
* Blackfire
* Optimise and monitor

#### Speed up Twig

- Speeding up Symfony
- ext/twig (PHP5 only, not PHP 7)
- Store compiled templates in Opcache, make sure it's enabled
- Render assets though the webserver (assetic not running all the time)

#### Edge side includes

- Component cached differently to the rest of the page
- Varnish/Nginx
- `render_esi`
- News block that caches frequently, rest of the page

#### HTTP/2 with Weblink

- slow finding CSS files to load - 'push' over CSS files, doesn't need to wait
- `preload()` - https://symfony.com/doc/current/web_link.html

#### Live updating pages
- Instantly update when sports results are updated, news articles are added
- Mercure - https://github.com/symfony/mercure
- LiveTwig - whole block or whole section, and live update `render_live`
- Turbolinks - replace whole body, keeps CSS and JS in memory. Merges new stuff in. `helthe/turbolinks`
- ReactPHP - shares kernel between requests

### Writing better code with Twig

- Keep templates simple. Avoid spaghetti code, only about UI. HTML or small amounts of Twig.
- Avoid delimeter chains
    - Bad:`blog_post.authors.first.user_account.email_address`
    - Good `{{ blog_post.authors_email_address }}`
    - Less brittle, slow
* Filters
    - Use filters to be precise
    - Custom filters
    - Avoid chains. Can cause odd results. Create a new filter in PHP
* Functions
    - Write your own functions
    - Simpler templates
    - Get data, can use boolean statements
* Components
    - Break a page into components rather than one large page
    - `include()`
    - Use `only` to only pass that data. less tightenly coupled.
    * `render` calls the whole of Symfony, boots Kernel, can be expensive and slow
    * Loosely couple templates and controllers
        - Keep responses simple
        - What makes sense
        - if you need extra data in the template, get it in the template
    * View models
        - Mixed results
        - `BlogPostViewModel`
        - Can result in boilerplate code
        - Can be useful if the view model is different to the Entity
    * DRY
        - "Don't repeat yourself"

+ Faster development
    * Separate UI tests from back-end tests. Different layers for different teams. People don't need to run everything if they are only changing certain things.
- Help your front end
    + Webpack - Encore
    + Type hinting in functions and filters, easier to debug
    + Logging
    + Friendly exceptions - help front-end devs by returning meaningful, readbale errors
    + Web Debug Toolbar and Profiler, provide training for toolbar and profilers
    + Twig-friendly development environment - Twig support in IDEs and text editors

SPAs are sometimes teh right solution. Why do they want to use it, can the same benefits be added with Twig?

3 most important points:

- Profile, identidy, optimise, monitor
- Loosely couple templates to your app code
- Help your front ends - put your front end developers first
- You don't need to use a SPA for single pages, use JavaScript for that one page. It doesn't need to be all or nothing.

## BDD Your Symfony Application (Kamil Kokot)

* Applying BDD to Sylius
* 2 years since release of Sylius (Symfony 2 alpha)
* The business part is more important than the code part

### What is BDD?
* Behaviour driven development. Combines TDD and DDD, into an agile methodology
* Encourages communication and creates shared understanding
* Living, executable documentation that non-programmers understand. Always correct.
* Feature file
    - Feature
    - Scenario - example of the behaviour for this feature. Simple, atomic. (e.g. I need a product in order to add it to a cart)
    - In order to...
    - Who gets the benefit?

### BDD in practice
* Feature: booking flight tickets
* Scenario: booking flight ticket for one person
    - Given there are the following flights...
    - When I visit '/flight/LTN-WAW'
    - Then I should be on '/flight/LTN-WAW'
    - Add I should see "Your flight has been booked." in "#result"
* In the BDD way - what is the business logic? What is the value for this scenario? What is the reason 'why', and who benefits from this?
    * We just need to know that there are 5 seats left on a flight
    * Talk and communicate about how the feature is going to work - not just developers
    * BDD aids communication
* Questions we can ask
    * Can we get a different outcome when the context changes?
        - When there was only one seat available
        - When there were no available seats
    * Can we get the same outcome when the event changes? Can we change 'When' and 'Then stays the same'
        - When it is booked for an adult and a child
        - When it is booked for an adult
    * Does anything else happen that is not mentioned?
        * Generate an invoice if a seat is booked
        * a pilot would like to get a notification that a seat was booked.
    - Figuring out the rules
        + Adults are 15+ years old
        + Children are 2-14 years old
        + Infants and children can only travel with an adult
        + We don't allow for overbooking
    * Translating rules into examples
        - Add a new scenario for each rule - e.g. don't allow over booking
            + "And the flight should be no longer available..."

### Behat

* Used to automate and execute BDD tests, also SpecDDD
* maps steps to PHP code
* Given a context, when an event, then an outcome
* Domain Context, API context
* class implements `Context`, annotations for `@Given`, `@When`, `@Then`. allows for arguments and regular expressions
* Suites: change what code is executed, and what scenarios are executed. context and tags
* FriendsOfBehat SymfonyExtension - integrates Behat with Symfony
    - Contexts registered as Symfony services - inject dependencies, service as a context in Behat. Need to be 'public' for it to work
    - Reduces boilerplate code. Supports autowiring.
    - Zero configuration

### Domain context
* `Given` verb matches `@Given` annotation. Same for `When` and `Then`.
* Transformers, type hint name string, return Client instance

### API context
* inject `FlightBookingService` and `KernelBrowser`
* Use `$this->kernelBrowser->request()`
* Use `assert()` function wuthin `@Then`

### Back to reality - how it's done with Sylius
* Business part applies to all context. Start talking about what needs to be done, start communicating
* Implement contexts for UI and API
* 12716 steps, 1175 scenarios, 8 min 8 sec, 2.4 scenarios /sec
* 12x faster than JS (17 min 48 sec, 0.19 scenario / sec)
* Treat test CI environment like production
    - Turn off debug settings, add caching
    - Enable OPcache

* Write features in a natural way
* Too many setup steps - merge steps. less visual debt. e.g. Create currency, zone and locale when creating a store
* Avoid scenarios that are too detailed. You should specify only what's important to this scenario.

## Migrating to Symfony one route at a time (Steve Winter)

* New client with an old application, built in an old version of another framework with unusual dependency management, no tests, no version control and deploying via FTP. Done over a ~3 month period.

* Subscription based index of suppliers
* New requirements to implement by the client
* Our requirements: Needed a deployment process, make it testable, fix the build chain
* Solution attempt 1: Migrate to a new version of the current framework
    - Minor template and design changes were fine
    - Modifiy features, add new dependencies.
* Solution attempt 2: Upgrade to the latest version - same outcome due to multiple BC breaks (no semver), lots of manual steps
* Solution attempt 3: Symfony!
    - Semver! Backwards compatibility promise
    - Symfony app to run in parallel, Apache proxy rules and minor changes to the legacy app, added data transfer mechanisms
    - Anything new done in Symfony
    - Installed on the same server with it's own vhost but not publicly accessible
    - Deployed independently of legacy app

### Apache proxy rules

Proxy `/public` to symfony app

### Legacy app

* Shared cookie for single login between apps - user account details (name etc), session details (login time)

### Added functionality

* Built in Symfony
* new proxy rules for new routes
* Add menu links to legacy app menu
* How do we show how many reminders are active?
    - Symfony based API called from the front-end

### Migrating routes

* Rebuilt or extend in Symfony app
* Test and deploy, then update the apache config to add new proxy rules

### A gotcha

- Legacy app uses CSRF
- Needed to track the token, added to shared cookie and pass through to the Symfony side

### Storing data

- Both apps using the same data with different credentials
- Some shared tables, some tables are specific to each app

### Remaining challenges

* User session management, still handled by legacy app
* Templating/CSS - two versions of everything
    - Next step: move all CSS to Symfony

### Summary

- Add Symfony app, Apache proxy rules for routes
- User transfer mechanisms
- New functionality added in Symfony

### Is this right for you?

It depends. Fine for a 'modest' size. Use a real proxy for larger scale apps, use different servers with database replication.

## Closing Keynote: The fabulous World of Emojis and other Unicode symbols (Nicolas Grekas)

* ASCII. Still used today. Map between the first 128 numbers to characters. OK for UK and US.
* 256 numbers in Windows-1252 (character sets). Each country had their own set.
* It's legacy. 0.2% for Windows-1252. 88.8% for UTF-8 (Feb 2017)
* Unicode: 130k characters, 135 scripts (alphabets)
* Validation errors using native alphabet - e.g. invalid last name when submitting a form
* 17 plans, each square is 255 code points
* Emojis are characters, not images
* Gliph is a visual representation of a character
* From code points to bytes
    - UTF-8: 1,2,3 or 4 bytes
    - UTF16: 2 or 4 bytes
    - UTF-32: 4 bytes
* UTF-8 is compatible with ASCII
* Case sensitivity - 1k characters are concerned. One uppercase letter, two lower case variants. Turkish exception (similar looking letters that are different letters with different meanings). Full case folding.
* Collations - ordering is depends on the language. 'ch' in Spanish is a single character.
* Single number in unicode to represent accents. Combining characters.
* Composed (NFC) and decomposed (NFD) forms - normalisation for comparison
* Grapheme clusters - multiple characters, but one letter as you write it (separate characters for letters and accent)
* Emjois - combining characters. e.g. Combine face with colour. Different codes and character names. Also applies to ligatures. A way to combine several images together into one single visual representation.

### unicode fundamentals

* uppercase, lowercase, folding
* compositions, ligatures
* comparistions - normalisations and collations
* segmentation: characters, words, sentences and hyphens
* locales: cultural conventions, translitterations
* identifiers & security, confusables
* display: direction, width

### unicode in practice

- MySQL - `utf*_*`. `SET NAMES utf8mb4` for security and storing emojis. Cannot store emojis with `utf8`

### in php

* `mb_*()`
* `iconv_*()`
* `preg_*()`
* `grapheme_*()` `normalizer_*()`
* `symfony/polyfill-*` - pure PHP implementation
* Made a component - **symfony/string** - https://github.com/symfony/symfony/pull/33553
* Object orientated api for strings. Immutable value objects
* `AbstractString`
    * `GraphemeString`
    * `Utf8String`
    * `BinaryString`
+ AbstractString - Methods to serialize, get length, to binary or grapheme or utf8
    * Methods for starts with, ends with, is empty, join, prepend, split, trim, title etc

