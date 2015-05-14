---
layout: v2/using-stories
title: Designing A Test
updated_for_v2: true
---

# Designing A Test

## Tests As Experiments

Think of a test as a miniture science experiment.

* You start with a hypothesis - a theory that you want to either prove or disprove.
* Decide what 'success' will look like.
* Work out the preconditions that your hypothesis relies on.
* What steps need to be performed to test your hypothesis?

At the end of this 4-step process, you'll have a test design that can be automated using Storyplayer.

## The Hypothesis

_You start with a hypothesis - a theory that you want to either prove or disprove._

For example, you might want to prove that your system under test has been installed correctly, or that someone can register for your website, or that they can logout.

The more detailed your hypothesis - the more specific you can be about what you want to prove or disprove - the more useful your test will be. You may find that you end up with a group of tests that together prove or disprove your original hypothesis.

For example, to prove that your system under test has been installed correctly, you may break that down into a test to make sure the RPM package is installed, a test to make sure that the database has been created, a test to make sure that the application's config file is present, a test to make sure that the app is writing to your log files, a test to make sure that your home page appears, a test to make sure that your 404 page appears, and so on.

## Outcomes

_Decide what 'success' will look like._

Before you start planning the individual steps for your test, identify what your system under test (and your test environment) will look like if your hypothesis is successful.

What will change? Can you identify all of the changes that will happen?

For example, if you're proving that someone can register for your website, successful registration means that the user will then be able to log into your website. Will there be a new record in the database for the user? Your admin user will probably be able to see that new record via your admin site. Will the new user receive a welcome email? That might be important - do they need the information in the welcome email to be able to login that first time? Do you have different types of registered user? Will the new user be registered as the correct type?

There's normally a lot more to a successful outcome than the user completing their actions without any 404 Not Found or 500 Internal Server Error pages appearing!

Make a note of all of the ways that you can identify that your hypothesis has been proven (or disproven!). These will become your post-test inspection steps.

## Preconditions

_Work out what preconditions your hypothesis relies on._

This is all about identifying your assumptions, and turning them into a guaranteed situation. The more you can control the state of your system under test and test environment before your test acts, the more reliable your test will be. You may hear this called _deterministic testing_.

For example, if you're proving that someone can register for your website, you may be assuming that they're not already registered for your website. You may be assuming that they've never visited your website before. You may be assuming the opposite - that they've arrived at your website via the landing page for a particular marketing campaign.

If your website is selling to businesses, you may be assuming that they're the first person from a given business to register. Or you might be assuming that they're registering to collaborate with colleagues from the same business. Or to collaborate with colleagues from other businesses.

Sometimes, your preconditions will contradict each other. You can turn those into separate tests, performing the same actions from different starting situations.

Make a note of the conditions that need to exist for your hypothesis to be testable. These will become your test setup steps.

## Actions

_What steps need to be performed to test your hypothesis?_

How do you get from your starting point - a system under test and test environment that meets your preconditions - to your desired outcome? What actions does the end-user perform? What order are they performed in?

For example, if you're proving that someone can register for your website, they may need to open the 'register' page in their web browser. Once the page has loaded, they may need to enter their email address and their chosen password into the registration form. Once the form has been filled out, they may need to submit the form.

Are there any additional steps that need doing? Does the user need to click on a 'confirm'-type link in an email? Do they have to make a payment before they can complete registration? Does a moderator need to manually approve their registration application?

Actions are all about what the end-user has to do - and what they are allowed to do. It can be tempting to make a story easier to test by adding a step that an end-user would never be allowed to do on the production server, such as writing data directly to the app's database. Don't do it. Your role here isn't to write a test - it's to prove that the end-user can use your app.

Your final list of steps become your test actions.

## Putting It All Together

Now that you have your test design, how do you translate that into a Storyplayer test?

* Add steps to your test's `testSetup()` to create the pre-conditions.
* Add steps to your test's `action()` to do exactly what your end-user is expected to do.
* Add steps to your test's `postTestInspection()` to double-check that the `action()` achieved all of the outcomes that you're expecting.
* If you have a `testSetup()`, add steps to your test's `testTeardown()` to undo everything done in the `testSetup()` phase, so that the next test starts from the same clean slate.

Run the test, and see how well your science experiment has worked.