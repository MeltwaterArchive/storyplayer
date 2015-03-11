---
layout: v2/learn-fundamentals
title: Belt and Braces Testing
prev: '<a href="../../learn/fundamentals/understanding-test-environments.html">Prev: Understanding Test Environments</a>'
next: '<a href="../../learn/test-your-code/index.html">Next: Test Your Code</a>'
updated_for_v2: true
---
# Belt And Braces Testing

One of the things that makes Storyplayer different is that it promotes what we call _belt and braces testing_. The best way to explain it is to give you an example.

## Our Scenario - Testing User Registration

Imagine you are writing tests for signing up for a free trial account for a web-based application. The test for this might look something like:

* generate username, password, email details
* goto the registration page
* fill out the registration form
* click the 'signup button'
* expect a 'congratulations' page to appear

If all of those steps complete successfully, the test passes. But is that enough?

## Avoid The Illusion Of Success

The problem with that test is that it suffers from _the illusion of success_. The test only looks at the surface - the user interface - and does nothing to verify that registering for a free trial account actually does anything at all.

When a user signs up for a free trial account, that will result in several knock-on effects, such as:

* the user will have a 'correct' user record in the database
* the user may have a billing record in the database
* the user will receive a 'thanks for registering' email

If signing up does not successfully create / trigger any of the above, does signing up really work, or does it simply appear to work?

Successfully signing up makes it possible for this user to do things that they could not do before:

* the user will now have access to some of the web app's functionality
* the user may still be denied access to some of the web app's functionalty
* the user will be able to login to the app now that they are registered

If the user can't do these things after signing up, does signing up really work, or does it simply appear to work?

Finally, most web apps have an 'admin' section of the site, where admin users can see the details of user accounts. If the new user doesn't appear there, has signing up really worked, or did it simply appear to work?

## Verify, Don't Assume

Use the power of Storyplayer to perform detailed checks to make sure that your story really has worked:

* Use the [PDO module](../../modules/pdo/index.html) to log into your databases to make sure that your web-based app has stored the correct information about your new user.
* Use the [IMAP module](../../modules/imap/index.html) to log into your email system to make sure that your web-based app has sent out any 'thanks for registering' emails - especially if your web app requires the user to confirm that they really did sign up for the service.
* In your test's [PostTestInspection phase](../../using/stories/post-test-inspection.html), use the browser to visit the sections of your web-based app that the user wasn't allowed to see until they signed up.
* Use the browser to login as your system's admin user, then go and use your web app's admin tools to prove that they can see the new user too.

We can say that the test passed __only__ if all of these detailed checks pass too. If _any_ of these checks fail, then the story didn't really work - it only _appeared to work_.

This is what we call _belt and braces testing_.

* The user's test steps must complete successfully. These go in your test's `Action` phase.
* The consequences of this test (the detailed checks) must also complete successfully. These go in your test's `PostTestInspection` phase.

## Write Tests For Alternative Scenarios Too

It's very unlikely that there's only the one test scenario for signing up for a web app. In practice, you'll find that there are other scenarios that also need to be covered, such as:

* What about signing up with a really long name? Does the signup process place a limit on how long a user's name can be? Is this limit too short?
* What about users who use non-ASCII characters in their name? Does the signup process cope with these correctly?
* Can a user attempt to signup with a username that has already been taken?
* Can a user attempt to signup with an email address that has already been used?
* Can a user attempt to signup using a Facebook login, or a Twitter login?
* Can a user signup and becoming a paying customer straight away (i.e. completely skip being a free trial user)?

... and so on.

Each of these examples is a different way of looking at the signup process. Each one is a separate test that needs to be written.

This is also part of what we call _belt and braces testing_.

* The system under test must place limits on what a user can do. These limits need testing to prove that they are enforced correctly and safely.  Each of these can form a separate test.
* The system under test may support multiple ways that a user can complete a story. Each of these scenarios needs its own test to prove that they continue to work as new versions of the system under test are released.

## Test Order Makes A Difference

The system under tests has state (database records, data in caches, cookies on the user's machine, and so on), and as users use the system under test, their actions change some of this state.  In computer science terms, the system under test is (ultimately) a [finite state machine](http://en.wikipedia.org/wiki/Finite-state_machine), and the actions that each user story performs form a [directed graph](http://en.wikipedia.org/wiki/Directed_graph).

For example, imagine that the web-app is being used by a husband and wife who share an iPad. If the wife has already signed up for the web-app, how does the husband then signup for his own account?

* iPads don't have user accounts like desktop and laptop computers do. (It's quite common for several people in the same household to share the same user account on their desktops and laptops too!)
* The browser on the iPad will have already been used to login to the wife's account on the system under test. This means that there are cookies already stored on the web browser.

The state at the start of the 'signup for a free trial account' test is now very different to what the simple test probably expected.  But this is state that our simple test did not explicitly create.

This is also part of what we call _belt and braces testing_.

* For each user story, there'll be multiple possible states that the system under test can be in when the story starts.
* You can specify which order Storyplayer runs tests in, so that you can chain tests together to create different states at the start of your test.

## Further Reading

Placeholder. We need to provide pages that answer these questions:

* How does a user specify the order that stories run in?
* How does a user specify the users that a story uses?