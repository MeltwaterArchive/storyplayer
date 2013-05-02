---
layout: stories
title: User Stories
prev: '<a href="../stories/index.html">Prev: Introducing Stories</a>'
next: '<a href="../stories/service-stories.html">Next: Service Stories</a>'
---

# User Stories

Why don't we talk about tests instead of Stories? Because Stories describe not only the results of a test, but also the path taken by the user to get those results.  This metaphor is specially useful when you are testing user-facing front-end modules, which may pass all tests a developer might think of, but still fail the user test.

Take for example a login page that shows two forms: login and registration. Let's assume both have a password field. A programmer might write a test that uses basic authntication with sample credentials and such test would return a positive result while the user might get confused and not know which password field he or she should use.  From the point of view of the user, the login test might fail or pass; it all depends on what the user thinks is the correct choice.  As you can see, User Stories can be used to construct test suites that more realistically mimic actual user behavior and thus allow QA teams test both the functionality of the software and the user experience.
