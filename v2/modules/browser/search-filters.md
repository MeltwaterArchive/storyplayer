---
layout: v2/modules-browser
title: Supported Search Filters
prev: '<a href="../../modules/browser/search-targets.html">Prev: Supported Search Targets</a>'
next: '<a href="../../modules/browser/fromBrowser.html">Next: fromBrowser()</a>'
---

# Supported Search Filters

_Search filters_ are used to reduce the list of DOM elements that match the _search target_ down into (normally) one specific search result.

## WithAltText

Use the _WithAltText_ search filter to find DOM elements that have a matching _alt_ attribute. An alternative is the [WithTitle](#withtitle) filter.

Example:

{% highlight php startinline %}
click()->elementWithAltText("Login Button");
{% endhighlight %}

This search filter normally gets used for clicking on images.

## WithClass

Use the _WithClass_ search filter to find DOM elements that have a matching CSS class attribute.

Examples:

{% highlight php startinline %}
$invoices = fromBrowser()->get()->cellsWithClass("invoice-number");
$errors = fromBrowser()->get()->fieldsWithClass("input-errors");
{% endhighlight %}

This search filter is very useful for extracting DOM elements (or their contents) where there's no label or fixed text to search for.

## WithId

Use the _WithId_ search filter to find DOM elements with a matching _id_ attribute.

Example:

{% highlight php startinline %}
usingBrowser()->click()->buttonWithId('submit_payment');
{% endhighlight %}

In general, you should use the [WithLabel / Labelled](#withlabel__labelled) or [WithText](#withtext) search filters wherever possible (because they search for things that your end-users should be able to see), and only fall back to using this search filter when an ID is all that you have to work with.

Common problems that stop this search filter from working when you expect it to include:

* __duplicate _id_ attributes on the page:__ the correct solution is to fix your web-based app to never have duplicate _id_ attributes.
* __id attributes that are unique each time the page loads:__ if you have to have dynamic _id_ attributes, make them deterministic, or wrap them in a _&lt;div&gt;_ container perhaps.

## WithLabel / Labelled

Use the _WithLabel_ or _Labelled_ search filter (they're identical) to find DOM elements that have an associated label.

Example:

{% highlight php startinline %}
usingBrowser()->type('storyplayer is great!')->intoFieldLabelled('Feedback');
{% endhighlight %}

Along with the [WithText](#withtext) search filter, this is one of the search filters to use as much as possible in your tests, because it searches for what your end-user should be able to see on the page.

This filter executes the following steps:

* find a label that has the text you specify
* look at the label's _for_ attribute - it should contain an ID
* look for the field with the _id_ attribute that matches the label's _for_ attribute

It only works for correctly marked up labels and form fields.  Common problems that stop this search filter from working when you expect it to are:

* __form fields with _name_ attributes but no _id_ attributes:__ the correct solution is to add _id_ attributes to all labelled form fields.
* __duplicate _id_ attributes on the page:__ the correct solution is to fix your web-based app to never have duplicate _id_ attributes on a single page.

## WithName / Named

Use the _WithName_ or _Named_ search filter (they're identical) to find DOM elements that have a matching _name_ attribute.

Example:

{% highlight php startinline %}
usingBrowser()->type('1111222233334444')->intoBoxNamed('cc_number');
{% endhighlight %}

In general, you should use the [WithLabel / Labelled](#withlabel__labelled) or [WithText](#withtext) search filters wherever possible (because they search for things that your end-users should be able to see), and only fall back to using this search filter when a name is all that you have to work with.  If you're constantly changing the text inside buttons and links, then you might need to use this search filter to avoid changing your tests a lot.

Common problems that stop this search filter from working when you expect it to include:

* __duplicate _name_ attributes on the page:__ this can commonly happen if you have a combined Registration / Login page with two forms on it. Use the [Form module](../form/index.html) to only act on a specified form.

## WithPlaceholder

Use The _WithPlaceholder_ search filter to find DOM elements that have a matching _placeholder_ attribute.

Example:

{% highlight php startinline %}
usingBrowser()->type('stuart')->intoBoxWithPlaceholder("Username ...");
{% endhighlight %}

This search filter is handy for testing that the placeholder (a visual clue) is present in the DOM.  Just be aware that it doesn't prove that the end-user can actually see the text.

## WithText

Use the _WithText_ search filter to find DOM elements that contain matching text.

Examples:

{% highlight php startinline %}
expectsBrowser()->has()->fieldWithText('Login');
usingBrowser()->click()->buttonWithText('Register');
{% endhighlight %}

Along with the [WithLabel / Labelled](#withlabel__labelled) search filter, this is one of the search filters to use as much as possible in your tests, because it searches for what your end-user should be able to see on the page.

## WithTitle

Use the _WithTitle_ search filter to find DOM elements that have a matching _title_ attribute.

Example:

{% highlight php startinline %}
usingBrowser()->click()->fieldWithTitle("Next Page");
{% endhighlight %}

This search filter normally gets used for clicking on images.  An alternative is the [WithAltText](#withalttext) filter.