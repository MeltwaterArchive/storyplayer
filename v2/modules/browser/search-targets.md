---
layout: v2/modules-browser
title: Supported Search Targets
prev: '<a href="../../modules/browser/searching-the-dom.html">Prev: Searching The DOM</a>'
next: '<a href="../../modules/browser/search-filters.html">Next: Supported Search Filters</a>'
---

# Supported Search Targets

_Search targets_ are used to determine the HTML tags that the _search term_ will search against inside the browser's current DOM.

## box / boxes

Use _box_ or _boxes_ to only search for _&lt;input&gt;_ tags in the DOM.

Example:

{% highlight php %}
usingBrowser()->type('hello!')->intoBoxWithLabel("First Name");
{% endhighlight %}

## button / buttons

Use _button_ or _buttons_ to only search for _&lt;input&gt;_ tags in the DOM.

Examples:

{% highlight php %}
usingBrowser()->click()->buttonWithText("Login");
expectsBrowser()->has()->buttonsWithClass("button");
{% endhighlight %}

## cell / cells

Use _cell_ or _cells_ to only search for _&lt;td&gt;_ tags in the DOM.

Example:

{% highlight php %}
$value = fromBrowser()->getText()->fromCellWithId('cell_1_1');
{% endhighlight %}

## dropdown / dropdowns

Use _dropdown_ or _dropdowns_ to only search for _&lt;select&gt;_ tags in the DOM.

Example:

{% highlight php %}
$value = fromBrowser()->getOptions()->fromDropdownWithLabel('Country');
{% endhighlight %}

## element / elements

Alias for [field / fields](field__fields).

## field / fields

Search against all elements in the DOM.  Use this when you don't want to limit the kinds of tags to search against.

This is often useful for faux buttons - an element that _looks_ like a button to the user, but underneath could be a real button (an &lt;input&gt; tag) or a false one (an &lt;a&gt; tag):

{% highlight php %}
$value = fromBrowser()->click()->fieldWithText('Login');
{% endhighlight %}

If you use _field_ as the search term, then you're safe no matter what your front-end developer is using for a button.

## heading

Use _heading_ to only search for _&lt;h1&gt;-&lt;h6&gt;_ tags in the DOM.

Example:

{% highlight php %}
$text = fromBrowser()->getText()->fromHeadingWithId('introduction');
{% endhighlight %}

## link / links

Use _link_ or _links_ to only search for _&lt;a&gt;_ tags in the DOM.

Example:

{% highlight php %}
$value = fromBrowser()->click()->linkWithText('Login');
{% endhighlight %}

## orderedlist

Use _orderedlist_ to only search for _&lt;ol&gt;_ tags in the DOM.

Example:

{% highlight php %}
$list = fromBrowser()->get()->orderedlistWithId('priorities');
{% endhighlight %}

## span

Use _span_ to only search for _&lt;span&gt;_ tags in the DOM.

Example:

{% highlight php %}
$amount = fromBrowser()->getText()->fromSpanWithClass('payment_total');
{% endhighlight %}

## unorderedlist

Use _unorderedlist_ to only search for _&lt;ul&gt;_ tags in the DOM.

Example:

{% highlight php %}
$list = fromBrowser()->get()->unorderedlistWithId('tasks');
{% endhighlight %}

## Default Search Target

If you use a search target that Storyplayer does not know about, it will assume that you mean any DOM element.

