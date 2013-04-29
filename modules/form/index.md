---
layout: modules-form
title: The Form Module
prev: '<a href="../../modules/file/usingFile.html">Prev: usingFile()</a>'
next: '<a href="../../copyright.html">Next: Legal Stuff</a>'
---

# The Form Module

## Introduction

The __Form__ module allows you to isolate and work with a specific form on a HTML page.

The source code for this Prose module can be found in these PHP classes:

* DataSift\Storyplayer\Prose\FormActions
* DataSift\Storyplayer\Prose\FormDetermine
* DataSift\Storyplayer\Prose\FormExpects

## Why A Separate Form Module?

Sometimes, web-based applications have multiple forms on a single page; and when that happens, the [Browser module](../modules/browser/index.html) can struggle to pick the right fields in the right form without a little help from you.

For example, an app's home page may include the _Registration_ form and the _Login_ form in the DOM, with only one of them visible to the user at a time.  Both forms may contain fields with the same name or label (such as _Username_ or _Password_).  When that happens, which form's Username will the Browser module pick?

To make sure that we can right robust tests in this situation, we've added the Form module.  Each of the Form module's objects requires a parameter - the _id_ attribute of the form that you want to work with, and its actions only work on DOM elements inside that specific _&lt;form&gt;_ element in the DOM.  That gives you confidence that you're testing the form you think you are, no matter how many other forms exist on the page.

## Dependencies

This module relies on the [Browser module](../browser/index.html). See [its list of dependencies](../browser/index.html#dependencies) for more information.

## Using The Form Module

The basic format of an action is:

{% highlight php %}
$st->MODULE($formId)->ACTION();
{% endhighlight %}

where __formId__ is the _id_ attribute of the form you wish to work with; __module__ is one of:

* _[fromForm()](fromForm.html)_ - get data from the specified form
* _[expectsForm()](expectsForm.html)_ - test the specified form and its contents
* _[usingForm()](usingForm.html)_ - enter data into the specified form, and submit it