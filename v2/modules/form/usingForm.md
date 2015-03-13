---
layout: v2/modules-form
title: usingForm()
prev: '<a href="../../modules/form/fromForm.html">Prev: fromForm()</a>'
next: '<a href="../../modules/fs/index.html">Next: The Filesystem Module</a>'
updated_for_v2: true
---

# usingForm()

_usingForm()_ allows you to interact with the specified form inside the page.

The source code for these actions can be found in the class `Prose\UsingForm`.

## Behaviour And Return Codes

Every action makes changes to the specified form loaded into the browser.

* If the action succeeds, the action returns control to your code, and does not return a value.
* If the action fails, an exception is throw. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every action will be successful.

## check()

Use `usingForm()->check()` to tick a checkbox or radio button.

{% highlight php startinline %}
usingForm('registration')->check()->boxWithLabel("T's & C's");
usingForm('checkout')->check()->radiobuttonWithLabel("Next Day Delivery");
{% endhighlight %}

The difference between `usingForm()->check()` and `usingForm()->click()` is that `check()` always leaves the checkbox ticked, even if it was already ticked. If you `click()` a ticked checkbox, this will untick the checkbox.

## clear()

Use `usingForm()->clear()` to clear out any values inside a form's input box.

{% highlight php startinline %}
usingForm('login')->clear()->fieldWithLabel("Username");
{% endhighlight %}

This is commonly used to remove any browser-supplied auto-complete data when filling out forms.

## clearFields()

Use `usingForm()->clearFields` to clear out any values currently set in the form.

{% highlight php startinline %}
usingForm('login')->clearFields();
{% endhighlight %}

This is commonly used to remove all browser-supplied auto-complete data before filling out a form.

## click()

Use `usingForm()->click()` to click on a button, link, or other element on the page.

{% highlight php startinline %}
usingForm('login')->click()->linkWithText("Login");
{% endhighlight %}

## fillInFields()

Use `usingForm()->fillInFields()` to complete a form's text and dropdown fields quickly.

{% highlight php startinline %}
usingForm('login')->fillInFields([
	"username" => "testUser",
	"password" => "storyplayerRocks"
]);
{% endhighlight %}

The array keys can be any of the label text, id attribute, or name attribute of the field to complete. If the field cannot be found, an exception is thrown.

## fillInFieldsIfPresent()

Use `usingForm()->fillInFieldsIfPresent()` to complete a form's text and dropdown fields quickly.

{% highlight php startinline %}
usingForm('login')->fillInFieldsIfPresent([
	"username" => "testUser",
	"password" => "storyplayerRocks"
]);
{% endhighlight %}

The array keys can be any of the label text, id attribute, or name attribute of the field to complete. If the field is not present, no error occurs.

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