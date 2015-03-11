---
layout: v2/learn-test-your-code
title: What Are You Testing?
prev: '<a href="../../learn/test-your-code/why-component-testing.html">Prev: Why Component Testing?</a>'
next: '<a href="../../learn/test-your-code/sample-layout-for-source-code-repo.html">Next: Sample Layout For Source Code Repo</a>'
updated_for_v2: true
---
# What Are You Testing?

Storyplayer is a tool that you use to automate your tests. Before you start writing your tests in Storyplayer, you need to know what you are testing and why.

## Find The Requirements

Software requirements - the spec - are fundamental to your testing. They describe what the component you're about to test is supposed to do. Your tests will validate how well the component meets these requirements.

Different types of projects will have different levels of formal requirements:

* __JFDI (aka Just Do It) projects__ are most likely to have little-to-no formal requirements of any kind. Open-source projects too also tend to have no formal requirements.

* __Waterfall projects (such as Prince2-managed projects)__ will probably have several requirement documents, which might go by names such as _Business Requirements_, _Functional Requirements_, and _Non-Functional Requirements_. These documents are likely to be authoritative.

* __"Agile" environments__ are most likely to have their requirements in the form of _user stories_.

Whatever the environment, you need to find the requirements that you are testing against.

## When There Are No Requirements

If there are no written requirements, then there's a decision to be made:

* is there anyone who can write up the requirements for you?
* can you write them up yourself, perhaps in collaboration with the component's author?
* or do you need to reject the component until there are written requirements to test against?

It's a very bad idea to start writing Storyplayer tests with no requirements whatsoever. Tests always have one of two outcomes: they pass, or they fail. Your tests don't validate what the component already does. Their purpose is to validate what the component is meant to do.

How are you going to decide whether a test should pass or should fail if no-one knows what the component is meant to do?

## Find The Design Authority

Every component will have one or two people who have the final say over what the component actually does.

* It might be the developer who wrote the component, or their team leader.
* It might be the technical architect for the project.
* It might be a technical product manager overseeing a particular area.

Whatever their job title, they are the component's _design authority_. He or she is the person who will answer your questions when you're not clear on what the component is supposed to do.

Find this person. You will have questions. If you can't get answers to your questions, you're going to struggle to write comprehensive tests for the component.

## Test Planning

Once you have the requirements, and you know who to turn to when you have questions about the requirements, then you're ready to start planning your tests. How to plan tests is a huge topic, and beyond the scope of this guide. However, each of our [worked examples](../worked-examples/index.html) includes information on how we planned the testing for each project covered.

When you have your plan, the next thing to do is to [create your skeleton files](sample-layout-for-source-code-repo.html) so that you can [design your test environment](defining-a-test-environment.html).