---
layout: v2/modules-form
title: expectsForm()
prev: '<a href="../../modules/form/index.html">Prev: The Form Module</a>'
next: '<a href="../../modules/form/fromForm.html">Next: fromForm()</a>'
updated_for_v2: true
---

# expectsForm()

_expectsForm()_ allows you to test a specific form inside the currently loaded HTML.  Use these tests to prove that your story can continue.

The source code for these actions can be found in the class `Prose\ExpectsForm`.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception.  _Do not catch exceptions thrown by these actions_.  Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## doesntHave()

Use `expectsForm()->doesntHave()` to ensure that the specified form _doesn't_ contain a specified DOM element or elements.  This is the direct opposite of _[expectsForm()->has()](#has)_.

{% highlight php startinline %}
expectsForm('registration')->doesntHave()->linkWithText("Login");
expectsForm('credit_card')->doesntHave()->anyFieldsWithClass("invoice");
{% endhighlight %}

See _[has()](#has)_ below for a longer discussion.

## has()

Use `expectsForm()->has()` to ensure that the specified form contains a specified DOM element or elements.

{% highlight php startinline %}
expectsForm('registration')->has()->linkWithText("Sign Up!");
expectsForm('login')->has()->linkWithText("Login");
{% endhighlight %}

Some web-based applications can show different content on the same URL, depending on whether the end-user is logged into the app or not.  You often see this on website home pages.  Using the _Form_ module, you can safely work with just the form that you want to, and not worry if there are fields duplicated in the other form.

## isBlank()

Use `expectsForm()->isBlank()` to ensure that an input field on the currently loaded HTML page is blank (i.e. has an empty 'value' attribute).

{% highlight php startinline %}
expectsForm('registration')->fieldWithId("username")->isBlank();
{% endhighlight %}

Note that the [browser search term](searching-the-dom.html) comes in the middle of this statement, rather than at the end.

## isNotBlank()

Use `expectsForm()->isNotBlank()` to ensure that an input field on the currently loaded HTML page is not blank (i.e. has an 'value' attribute that is not empty).

{% highlight php startinline %}
expectsForm('shopping_cart')->firstFieldWithLabel("quantity")->isNotBlank();
{% endhighlight %}

Note that the [browser search term](searching-the-dom.html) comes in the middle of this statement, rather than at the end.

## isChecked()

Use `expectsForm()->isChecked()` to ensure that a checkbox or radio button on the currently loaded HTML page has been selected.

{% highlight php startinline %}
expectsForm('registration')->fieldWithLabel("I have read the terms and conditions")->isChecked();
{% endhighlight %}

Note that the [browser search term](searching-the-dom.html) comes in the middle of this statement, rather than at the end.

## isNotChecked()

Use `expectsForm()->isNotChecked()` to ensure that a checkbox or radio button on the currently loaded HTML page has not been selected.

{% highlight php startinline %}
expectsForm()->firstFieldWithLabel("I have read the terms and conditions")->isNotChecked();
{% endhighlight %}

Note that the [browser search term](searching-the-dom.html) comes in the middle of this statement, rather than at the end.