---
layout: v1/modules-browser
title: fromBrowser()
prev: '<a href="../../modules/browser/search-filters.html">Prev: Supported Search Filters</a>'
next: '<a href="../../modules/browser/expectsBrowser.html">Next: expectsBrowser()</a>'
---

# fromBrowser()

_fromBrowser()_ allows you to extract information and [WebDriverElement objects](webdriver.html) from the currently loaded HTML page.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\FromBrowser_.

## Behaviour And Return Codes

Every action returns either a value on success, or `NULL` on failure.  None of these actions throw exceptions on failure.

## has()

Use `$st->fromBrowser()->has()` to work out whether the DOM contains the content that you're looking for.

{% highlight php %}
if ($st->fromBrowser()->has()->buttonWithText('Register')) {
	// we are on the registration form
}
else {
	// we are on the login form
}
{% endhighlight %}

This action is normally used inside [local Prose dialects](../../prose/local-dialects.html), where you're building wrappers for your app's actions.

__See Also:__

* [expectsBrowser()->has()](expectsBrowser.html#has)

## get()

Use `$st->fromBrowser()->get()` to get one or more [WebDriver element objects](webdriver.html#webdriver_elements) from the DOM.

{% highlight php %}
$element = $st->fromBrowser()->get()->tableWithId('results');
{% endhighlight %}

This action is normally used when you need to run a custom XPath query to extract content from a DOM element that cannot be found by any other means.  This should be your last resort, as these kind of XPath queries are quite fragile, and take a lot of maintenance.

## getName()

Use `$st->fromBrowser()->getName()` to get the _name_ attribute from a specific DOM element.

{% highlight php %}
$name = $st->fromBrowser()->getName()->fromFieldWithLabel("Username");
{% endhighlight %}

This action is normally used when you want to perform a low-level check on the HTML markup of your forms, to make sure that the form is defining the input fields that your server-side code is expecting.

It's also handy for web pages where the _name_ attribute is being used outside of forms.  In this case, it is often worth revisiting the HTML markup to see whether _id_ or _class_ attributes need to be introduced / tweaked instead.

## getNames()

Use `$st->fromBrowser()->getNames()` to get the _name_ attribute from a set of specified DOM elements.

{% highlight php %}
$names = $st->fromBrowser()->getNames()->ofFieldsWithClass('input-error');
{% endhighlight %}

This action is normally only used with web pages where the _name_ attribute is being used outside of forms.  In this case, it is often worth revisiting the HTML markup to see whether _id_ or _class_ attributes need to be introduced / tweaked instead.

## getOptions()

Use `$st->fromBrowser()->getOptions()` to get the list of possible values from a _&lt;select&gt;_ list.

{% highlight php %}
$options = $st->fromBrowser()->getOptions()->fromDropdownWithLabel("Country");
{% endhighlight %}

This action is normally used for making sure that the end-user has the choices that are expected - especially if the dropdown list is dynamically generated.  For example:

{% highlight php %}
// the choices that should be available
$expectedOptions = array (
	"Gold Subscription Plan" => "gold",
	"Silver Subscription Plan" => "silver",
	"Bronze Subscription Plan" => "bronze"
);

// the choices that *are* available
$actualOptions = $st->fromBrowser()->getOptions()->fromDropdownWithLabel("Payment Plan");

// make sure the right choices are there
$st->expectsArray($actualOptions)->equals($expectedOptions);
{% endhighlight %}

## getTag()

Use `$st->fromBrowser()->getTag()` to get the HTML tag used by a specified DOM element.

{% highlight php %}
$tag = $st->fromBrowser()->getTag()->ofFieldWithText("Login");
{% endhighlight %}

This action is normally used when you want to perform a low-level check on the HTML markup of your page.

## getText()

Use `$st->fromBrowser()->getText()` to get the contents of the specified DOM element.

{% highlight php %}
$text = $st->fromBrowser()->getText()->fromFieldWithClass("total-amount");
{% endhighlight %}

This action is normally used when you want to check that the expected information is present on the page, for example:

{% highlight php %}
$expectedAmount = "$60";
$actualAmount = $st->fromBrowser()->getText()->fromFieldWithClass("total-amount");
$st->expectsString($actualAmount)->equals($expectedAmount);
{% endhighlight %}

## getTopElement()

Use `$st->fromBrowser()->getTopElement()` to get the DOM element that's at the top of the document loaded in the browser.  This is always the element created by the _&lt;html&gt;_ tag.

{% highlight php %}
$topElement = $st->fromBrowser()->getTopElement();
{% endhighlight %}

This action returns a _[WebDriverElement](webdriver.html)_, which you can then use to perform your own operations on directly.  We make this available so that you can use Selenium functionality that isn't yet supported by the _Browser_ module.

## getTitle()

Use `$st->fromBrowser()->getTitle()` to get the _&lt;title&gt;_ of the currently loaded page.

{% highlight php %}
$title = $st->fromBrowser()->getTitle();
{% endhighlight %}

This action is normally used inside [local Prose dialects](../../prose/local-dialects.html), where your Prose might be wrapping up a complex operation that spans multiple pages.

## getValue()

Use `$st->fromBrowser()->getValue()` to get the _value_ attribute of a specified DOM element.

{% highlight php %}
$username = $st->fromBrowser()->getValue()->ofBoxWithLabel('Username');
{% endhighlight %}

This action is normally used for checking that _Remember Me_-like functionality is working.