---
layout: stories
title: User Stories
prev: '<a href="../stories/index.html">Prev: Introducing Stories</a>'
next: '<a href="../stories/service-stories.html">Next: Service Stories</a>'
---

# User Stories

This chapter offers an introduction to the concept of _user stories_.  If you're new to _user stories_, you can find more information from our recommended [further reading](#further_reading).

## What Is A User Story?

A _user story_ is:

* a simple statement that describes __one__ action, and who can perform that action
* a record of the conversations about this action, providing more detail about the action
* an (incomplete) list of acceptance tests to help everyone understand when a story is complete

For example:

* subscription users can see all of their invoices

is a simple statement that describes one action (being able to see all of their invoices), and clearly states who can perform the action (subscription users).

From here, there'd probably be a conversation to decide where the subscription users would go to find their invoices (perhaps in a My Account section), and there'd probably be some acceptance tests such as _test with unpaid invoices, test with paid invoices, test with invoices outstanding after 30 days_ and so on.

## Good Stories Are Long-Lived

User stories focus on the _what_, and not the _how_ - and even the _what_ is usually a very open description.

As a result, although the software that delivers the story may change a lot over the years, the user story itself normally remains unchanged until you drop that feature completely from your software.  This _long-lived_ property adds a lot of stability to your software, because the need to meet the requirement remains no matter what else changes.

For example:

* subscription users can see all of their invoices

This story doesn't change, no matter if we move from self-hosted PDFs to showing customers invoices through a cloud-based finance package.  The implementation (and our tests) change, but we still have to satisfy the same basic requirement.  That brings a lot of clarity to testing, and makes it very easy to show where new releases of software are going to break customers' existing expectations.

## INVEST In Your Stories

The best user stories satisfy the INVEST checklist:

* Independent
* Negotiable
* Valuable to users or customers
* Estimatable
* Small
* Testable

For example:

* subscription users can see all of their invoices

is _independent_ (it doesn't rely on any other stories); is _negotiable_ (it doesn't state what an invoice looks like, or how a user gets access to an invoice); is _valuable to users or customers_ (an invoice is important to a user); is _estimatable_ (it's a specific requirement, not a sprawling programme of work); is _small_ (we only have to display invoices); and is _testable_ (we can write a story test in Storyplayer to prove that subscription users can see their invoices).

## At The Heart Of The Process

The great strength of user stories is that, done right, they can form the spine of your entire software development process.

* They list the functionality that your product people are asking for.
* They list the functionality that your project manager needs to deliver.
* They list the functionality that your developers need to turn into code.
* They list the functionality that your software testers need to test for.
* They list the functionality that your product people can do their acceptance testing against.

They don't need _interpretation_: everyone in your organisation can understand them, because they are not written in highly-technical terms.  They __do__ need clarification, and that's one of the reasons why it's important to also capture the conversations about a user story.  (Your conversations should also capture all of the _why_ for a story, so that the reasons behind a story's implementation are never forgotten).

## Further Reading

User stories originate from Kent Beck's book _[Extreme Programming Explained: Embrace Change](http://bit.ly/pUMx8J)_.  In the current edition, you'll find _Stories_ is listed as one of the _Primary Practices_ of Extreme Programming.

But the definitive work on the subject is Mike Cohn's _[User Stories Applied: For Agile Software Development](http://bit.ly/byqR4X)_, and this is the book that you should read if you plan on placing user stories at the heart of your software development process.