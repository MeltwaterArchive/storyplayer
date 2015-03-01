---
layout: v2/modules-form
title: usingForm()
prev: '<a href="../../modules/form/fromForm.html">Prev: fromForm()</a>'
next: '<a href="../../modules/fs/index.html">Next: The Filesystem Module</a>'
---

# usingForm()

_usingForm()_ allows you to interact with the specified form inside the page.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\UsingForm_.

## Behaviour And Return Codes

Every action makes changes to the specified form loaded into the browser.

* If the action succeeds, the action returns control to your code, and does not return a value.
* If the action fails, an exception is throw. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every action will be successful.

## check()

Use `usingForm()->check()` to tick a checkbox.

{% highlight php startinline %}
usingForm('registration')->check()->boxWithLabel("T's & C's");
{% endhighlight %}

## clear()

Use `usingForm()->clear()` to clear out any values inside a form's input box.

{% highlight php startinline %}
usingForm('login')->clear()->fieldWithLabel("Username");
{% endhighlight %}

This is commonly used to remove any browser-supplied auto-complete data when filling out forms.

__See Also:__

* _[usingForm()->fillOutFormFields()](#fillOutFormFields)_

## click()

Use `usingForm()->click()` to click on a button, link, or other element on the page.

{% highlight php startinline %}
usingForm('login')->click()->linkWithText("Login");
{% endhighlight %}

## select()

Use `usingForm()->select()` to pick an option in a dropdown list.

{% highlight php startinline %}
usingForm('registration')->select("United Kingdom")->fromDropdownWithLabel("Country");
{% endhighlight %}

_select()_ takes one parameter - the text of the option that you want to select.

## type()

Use `usingForm()->type()` to send a string of text to a selected DOM element.

{% highlight php startinline %}
usingForm('feedback')->type("Storyplayer lives!")->intoFieldWithLabel("comments");
{% endhighlight %}

You can also use _type()_ to send a mixture of normal text and non-printing keys, using the constants defined in _DataSift\WebDriver\WebDriverKeys_.  For a full discussion of how that works, please see _[usingBrowser()->type()](../browser/usingBrowser.md#type)_.