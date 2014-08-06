---
layout: v2/environments
title: Testing Against Production
prev: '<a href="../environments/dedicated.html">Prev: Testing Against Dedicated Environments</a>'
next: '<a href="../environments/multiple-environments.html">Next: Testing Against Multiple Environments</a>'
---

# Testing Against Your Production Environment

You can do all the testing in the world before you ship your code; none of it is worth a damn if your code doesn't work once it has been deployed into your production environment.  Traditionally, automated test tools have focused on _pre-release testing_.  Storyplayer allows you to run your stories against production too.

Testing against production is mostly a case of treating production as yet-another-dedicated environment, with one important caveat.

## Setting Up For Testing

You are going to run Storyplayer on your local computer, and use it to test the software installed onto your production environment.

You will need:

* [Storyplayer installed locally](../installation.html)
* Your existing stories, that you've already used in your pre-release testing

## Configuring Your Environment

Production is just like any other pre-existing dedicated environment: you run your stories against what is already there, and Storyplayer isn't responsible for deploying the environment in the first place.  Follow the advice in our guide to [Configuring Your Environment when Testing Against Dedicated Environments](dedicated.html#configuring_your_environment).

If you're adopting Storyplayer to help you test a product or service that has been around for some time, you might find yourself starting with tests that run against your production environment, and then adapting them to be used as part of your pre-release testing.  If that's the case for you, make sure you put all of your environment config options in _environments->defaults_ for now; you can break them up later as you expand the list of environments that you're testing against.

## Safeguarding Your Stories

Not every story you've written can be safely tested against production.  You can't afford to have any stories that are destructive (such as _Delete All Users_) run on production, otherwise very quickly you'll have no customers and no business!

The way to avoid having to reach for your database backups urgently is to [safeguard your production environment](safeguarding.html).  A safeguarded environment is one where stories cannot run by default; you have to add a line of code to each story to say that it is safe to run against that particular environment.

## Writing Your Stories

The key thing when writing stories to run against your production environment is that you should never cheat: your tests should do only what a customer can do.  You'll be tempted to take advantage of any internal services (especially when trying to prove that a test has succeeded), or to use a lot of XPath queries to read from (and interact with) your web pages.  Don't.

In production, if your tests have to cheat at all, it's nearly always because your story is trying to test something that is incomplete, or is too complex for Storyplayer to interact well with.  If you're finding it difficult to interact with your production app via Storyplayer, that normally means that your customer is going to have the same sort of difficulties too.  All Storyplayer is doing is showing you where these difficulties are.  In these cases, simply mark the story as _untestable_ for now.

## Running Your Stories

Running your stories against production is just like running your stories against any other dedicated environment.  Use the `-e` switch to tell Storyplayer which environment you want to run against:

<pre>
vendor/bin/storyplayer -e production &lt;storyfile&gt;
</pre>