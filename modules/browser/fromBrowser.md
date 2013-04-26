---
layout: modules-browser
title: fromBrowser()
prev: '<a href="../../modules/browser/search-filters.html">Prev: Supported Search Filters</a>'
next: '<a href="../../modules/browser/expectsBrowser.html">Next: expectsBrowser()</a>'
---

# fromBrowser()

_fromBrowser()_ allows you to extract information and [WebDriverElements](webdriver.html) from the currently loaded HTML page.

## has()

Use _$st->fromBrowser()->has()_ to work out whether the DOM contains the content that you're looking for.

{% highlight php %}
if ($st->fromBrowser()->has()->buttonWithText('Register')) {
	// we are on the registration form
}
else {
	// we are on the login form
}
{% endhighlight %}

This action is normally used inside [local Prose dialects](../../prose/local-dialects.html), where you're building wrappers for larger (and sometimes complex) actions inside your app.

### See Also

* [expectsBrowser()->has()](expectsBrowser.html#has)

## get()

## getName()

## getNames()

## getOptions()

## getTag()

## getText()

## getTitle()

## getValue()
