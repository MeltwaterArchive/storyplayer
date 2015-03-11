---
layout: v2/learn-test-your-code
title: Designing Component Tests
prev: '<a href="../../learn/test-your-code/recommended-first-tests.html">Prev: Recommended First Tests</a>'
next: '<a href="../../learn/test-your-code/writing-component-tests.html">Next: Writing Component Tests</a>'
updated_for_v2: true
---
# Designing Component Tests

You've [created your test environment](defining-your-test-environment.html), [created a config file for your system under test](defining-your-system-under-test.html), and successfully [created and run some initial tests](recommended-first-tests.html) for your component. It seems like a lot to do to get started, but once you've built tests for a few components, you'll see that really it's all pretty straight forward.

The hard work comes in writing, running, and refining the tests that your component needs. In a larger organisation, someone will have already prepared a test strategy for you to follow. But if they haven't, here are some ideas you can use to design your component tests.

## What To Test For

The vast majority of [user stories](../fundamentals/user-stories.html) and [service stories](../fundamentals/service-stories.html) will involve one or more of:

* Inputs and outputs
* Persistent data
* User interface (if there is one)
* Reports (if it generates any)

Let's look at each of these in detail.

## Inputs And Outputs

In a [service-oriented architecture](http://en.wikipedia.org/wiki/Service-oriented_architecture), your component will have an API that accepts input requests and sends responses back.  More complex components in larger environments may also synchronise data with other components.

For each API call, you can write tests for:

* a successful call (i.e. use it as documented / specified)
* each documented error condition
* a call with partial data (i.e. do not send some of the data that is required)
* a call with no data (i.e. do not send any data at all)
* a call with bad data (i.e. data values that should be rejected)
* a call with badly-formatted data (i.e. invalid JSON or XML or SOAP)

<div class="callout warning" markdown="1">
#### The World Runs On UTF-8 Now

It's really easy to fall into the trap of writing all of your test data in [ASCII](http://en.wikipedia.org/wiki/ASCII) alone. However, in today's global economy, it's very unlikely that your component only needs to accept ASCII data.

The web, and any JSON-based APIs that you have, fully supports the [UTF-8](http://en.wikipedia.org/wiki/UTF-8) character encoding standard today. Unfortunately, support for UTF-8 varies a lot from programming language to programming language, and even if your component has been written to support UTF-8, it may inadvertently rely on third-party code libraries that do not cope well with UTF-8 data.

Make sure that your test data includes both plain ASCII and UTF-8 data.

If your component has a JSON-based API, you need to be aware that the JSON standard requires some UTF-8 characters to be encoded as a UTF-16 pair. You'll need to include some of these characters in your test data too, just in case your JSON encode/decode library doesn't cope with them.
</div>

## Persistent Data

Your component may store data in a datastore of some kind, such as a MySQL database or key/value cache such as Memcached. It's vital that you only store 'correct' data into these datastores, and that this data doesn't get damaged by un-intentional side effects of unrelated operations.

Most of this data will be created, updated or deleted (the C, U and D from the [CRUD paradigm](http://en.wikipedia.org/wiki/Create,_read,_update_and_delete)) as a results of inputs that you're already testing for. You can extend your existing tests to inspect your datastores too.

For each API call and user-interface event, you can write tests for:

* proving that new data is correctly written to the datastore
* proving that existing data is correctly updated in the datastore
* proving that existing data is correctly deleted in the datastore
* only the data that should be created / updated / deleted is modified

<div class="callout info" markdown="1">
#### Write Once, Read Many

If you are going to run multiple copies of your component in production, then it's a good idea to follow the _write once, read many_ test strategy.

1. deploy multiple copies of your component into your test environment
1. send a create, update or delete input to just one of those copies
1. inspect the datastore to make sure it holds the expected data
1. send a read input to every copy of your component, to make sure that all of them return the expected data

This simple test catches any basic caching mistakes in your component, by requiring your components to be consistent by the time you perform the read.
</div>

<div class="callout warning" markdown="1">
#### Bad Data Can Break Good Code

Most code assumes that data already in a datastore is 'safe'. There's few (if any) checks to validate the data from a datastore. These checks can be very difficult to write well.

This places even more importance on making sure that your component is writing the 'correct' data into its datastore in the first place.
</div>

<div class="callout warning" markdown="1">
#### Data Integrity Is A Compliance Issue

Most countries in the world, and many industries too, have either legal or code-of-conduct rules governing the accuracy, relevancy and security of the data that your component stores. Storing 'bad' data can not only result in a badly-behaving component, but can also lead to bad publicity
</div>

## User Interface

Your component may have:

* an end-user interface (one that you expose to your customers directly)
* an admin interface (one that is only available to your sysadmins)

There should be requirements or user stories for everything that needs testing with the user interface.

As with the Input/Output tests discussed above, you can extend your user interface stories to perform deep inspection on the persistent data that's created, updated and destroyed by using the user interface.

<div class="callout info" markdown="1">
#### Better To Write End-To-End Tests?

If your component is part of a large platform, it might need most of that platform for the end-user interface to work. In that situation, you might be better off testing your end-user interface as part of your [platform tests](../test-your-platform/index.html), rather than writing component tests.

It's a judgement call.
</div>

## Reports

Your component may generate data on a time cycle, such as an end-of-day analytics report or an end-of-month billing run that spits out invoices for customers. The report may be triggered by a `cron` job, or there may be a scheduler built into your component itself.

For each report, you can write tests for:

* proving that the report generation starts on time
* proving that the report generation finishes in a timely manner
* proving that a 'correct' report is generated
* proving that the report generation skips over 'bad' data (rather than crashing and failing to produce a report at all)
* proving that the component's other functionality continues to work whilst the report is being generated

<div class="callout info" markdown="1">
#### Turn Back Time

Component tests run in their own test environment. You can do anything to the test environment that you need to do. That includes changing the test environment's clock to be whatever time and date will trigger generating your reports.

When your component is in production, it is the time and date that will trigger these reports. Re-create these conditions to prove that your reports will run when they are expected to. That gives you a more thorough test than simply forcing the report to happen on demand.
</div>

## Reported Bugs

Finally, as and when you're sent bug reports to analyse and fix, you can write a test for each of these. Together with your existing functional tests, this gives you a regression test suite that you can use to make sure that a bug stays fixed in future releases.

<div class="callout info" markdown="1">
#### Playing Catchup

If you need somewhere to start, start by writing tests for all reported bugs. Make it policy to write tests for all bugs reported in the future.  Do this, and you'll be surprised at how quickly you build up a test suite that makes a difference.
</div>