---
layout: v2/learn-test-your-code
title: Final Thoughts
prev: '<a href="../../learn/test-your-code/writing-component-tests.html">Prev: Writing Component Tests</a>'
next: '<a href="../../learn/test-your-platform/index.html">Next: Test Your Platform</a>'
updated_for_v2: true
---
# Final Thoughts

## Storyplayer Is Just A Tool

The vast majority of the advice in this guide isn't about how to use Storyplayer; it's advice on how to do component testing. This is a GoodThing(tm).

Storyplayer is just a tool. It's a very powerful tool, one that allows you to quickly create any tests that you need (and then use deep inspection afterwards to make sure that the test really did pass). We created it because we couldn't find anything else that allowed us to easily and reliably test in this way. But it's just a tool.

At the end of the day, the fundamentals of testing are still what's most important:

* __requirements__: knowing what the component is supposed to do, whether that's a large design document, a collection of stories, or a photo of a diagram on a whiteboard
* __experiments__: tests that check whether the component meets the requirements or not
* __verification__: knowing how to check whether the component really did what you just asked it to do
* __repeatability__: designing tests that work the same way every time they are run

## Looking For More Detail About Using Storyplayer?

Further reading:

* We've put together some [worked examples](../worked-examples/index.html) of real tests for real projects to show you how to use Storyplayer in detail.
* We've also built a [Howtos and Tips section](../../tips/index.html) where you can find a growing list of quick answers to your Storyplayer questions.