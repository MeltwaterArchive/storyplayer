---
layout: v2/using-stories
title: Service Stories
prev: '<a href="../stories/user-stories.html">Prev: User Stories</a>'
next: '<a href="../stories/story-test.html">Next: The Story Test</a>'
---

# Service Stories

Unless you have a very simple application, testing [user stories](user-stories.html) alone isn't enough to drive up the quality of your app.  Your user story tests only touch the edges of your software - the interfaces that your users have access to.  What about all of your backend services - the internal services and interfaces that your frontend relies on?

That's where _service stories_ come in.

## What Is A Service Story?

A _service story_ is a user story, except that it describes the behaviour of your backend systems.  For example:

* The billing system will generate invoices on the 1st of the month.

There's no _user_ in that story - we've replaced it with the _service_ instead, but everything else about a user story applies. It's still written in plain English, and as a high-level requirement. It should still be accompanied with the _conversation_ to capture any detailed decisions that have been made. And it should still be accompanied with an (incomplete) list of acceptance tests to help everyone decide when the story's implementation is complete.

Service stories still satisfy [the INVEST checklist](user-stories.html#invest_in_your_stories).

## Service Stories Help With Planning User Stories

One of the common pitfalls with user stories is the extra work that comes to light only after implementation has begun.  This often happens when people fail to think through all of the internal services that are needed to support the frontend code.

If you work to identify your required service stories when you're planning your user stories, then you've got a better idea of how much work needs to be done.  You've also got a better list of work to put onto your burndown chart, because you've broken one story up into more stories.  And because service stories are independent, you can ask different people to implement the service stories in parallel, which can help you cut down the amount of time it takes to get a new feature out of the door and to market.

## Service Stories Help With Improving Quality

A library of service stories gives you a clear list of all the things that any one of your backend services needs to be able to do.  _That allows you to test each backend service independently._  Why does this matter?

* Anything that's independent is a smaller thing to test.  It's much easier to focus on the quality of a single backend service at a time, and it's easier to work towards good test coverage when you're testing something that's small and independent. Knowing that each of your backend services works as intended gives you a much more solid platform to keep building on.
* Anything that's independent can be done in parallel.  You can test multiple backend services in parallel, making it easier to release new versions to production. Testing one service directly is quicker than testing your changes through end-to-end user story tests.

## Further Reading

I'm afraid that you won't find much out there about _service stories_, sorry. To the best of my knowledge, this is a term that we've invented.

Most of the advice on testing that exists today is focused either on _user story testing_ or _unit testing_.  Service stories fall into a middle ground that people currently don't talk about (perhaps because it was too expensive to do well before Storyplayer came along?)  With the release of Storyplayer, we're hoping to change that.
