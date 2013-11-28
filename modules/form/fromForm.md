---
layout: modules-form
title: fromForm()
prev: '<a href="../../modules/form/expectsForm.html">Prev: expectsForm()</a>'
next: '<a href="../../modules/form/usingForm.html">Next: usingForm()</a>'
---

# fromForm()

_fromForm()_ allows you to extract information and [WebDriverElement objects](webdriver.html) from a specified form on the currently loaded HTML page.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\FromForm_.

## Behaviour And Return Codes

Every action returns either a value on success, or `NULL` on failure.  None of these actions throw exceptions on failure.

## has()

Use `$st->fromForm()->has()` to work out whether the form contains the content that you're looking for.

{% highlight php %}
if ($st->fromForm('registration')->has()->buttonWithText('Register')) {
	// we are on the registration form
}
else {
	// we are on the login form
}
{% endhighlight %}

This action is normally used inside [local Prose dialects](../../prose/local-dialects.html), where you're building wrappers for your app's actions.

__See Also:__

* [expectsBrowser()->has()](../browser/expectsBrowser.html#has)
* [expectsForm()->has()](expectsForm.html#has)

## get()

Use `$st->fromForm()->get()` to get one or more [WebDriver element objects](webdriver.html#webdriver_elements) from the specified form.

{% highlight php %}
$element = $st->fromForm('registration')->get()->fieldWithId('hidden_field');
{% endhighlight %}

This action is normally used when you need to run a custom XPath query to extract content from a DOM element that cannot be found by any other means.  This should be your last resort, as these kind of XPath queries are quite fragile, and take a lot of maintenance.

## getName()

Use `$st->fromForm()->getName()` to get the _name_ attribute from a specific DOM element in the specified form.

{% highlight php %}
$name = $st->fromForm('registration')->getName()->fromFieldWithLabel("Username");
{% endhighlight %}

This action is normally used when you want to perform a low-level check on the HTML markup of your forms, to make sure that the form is defining the input fields that your server-side code is expecting.

It's also handy for web pages where the _name_ attribute is being used outside of forms.  In this case, it is often worth revisiting the HTML markup to see whether _id_ or _class_ attributes need to be introduced / tweaked instead.

## getNames()

Use `$st->fromForm()->getNames()` to get the _name_ attribute from a set of specified DOM elements in the specified form.

{% highlight php %}
$names = $st->fromForm('registration')->getNames()->ofFieldsWithClass('input-error');
{% endhighlight %}

This action is normally only used with web pages where the _name_ attribute is being used outside of forms.  In this case, it is often worth revisiting the HTML markup to see whether _id_ or _class_ attributes need to be introduced / tweaked instead.

## getOptions()

Use `$st->fromForm()->getOptions()` to get the list of possible values from a _&lt;select&gt;_ list in the specified form.

{% highlight php %}
$options = $st->fromForm('personal_details')->getOptions()->fromDropdownWithLabel("Country");
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
$actualOptions = $st->fromForm('subscription_plan')->getOptions()->fromDropdownWithLabel("Payment Plan");

// make sure the right choices are there
$st->expectsArray($actualOptions)->equals($expectedOptions);
{% endhighlight %}

## getTag()

Use `$st->fromForm()->getTag()` to get the HTML tag used by a specified DOM element in the specified form.

{% highlight php %}
$tag = $st->fromForm('login_form')->getTag()->ofFieldWithText("Login");
{% endhighlight %}

This action is normally used when you want to perform a low-level check on the HTML markup of your page.

## getText()

Use `$st->fromForm()->getText()` to get the contents of the specified DOM element in the specified form.

{% highlight php %}
$text = $st->fromForm('checkout')->getText()->fromFieldWithClass("total-amount");
{% endhighlight %}

This action is normally used when you want to check that the expected information is present on the page, for example:

{% highlight php %}
$expectedAmount = "$60";
$actualAmount = $st->fromForm('checkout')->getText()->fromFieldWithClass("total-amount");
$st->expectsString($actualAmount)->equals($expectedAmount);
{% endhighlight %}

You can use it to inspect any element inside the form - it doesn't have to be a form field itself.

## getTopElement()

Use `$st->fromForm()->getTopElement()` to get the specified form's _&lt;form&gt;_ DOM element.

{% highlight php %}
$topElement = $st->fromForm('registration')->getTopElement();
{% endhighlight %}

This action returns a _[WebDriverElement](webdriver.html)_, which you can then use to perform your own operations on directly.  We make this available so that you can use Selenium functionality that isn't yet supported by the _Form_ module.

## getValue()

Use `$st->fromForm()->getValue()` to get the _value_ attribute of a specified DOM element in the specified form.

{% highlight php %}
$username = $st->fromForm('registration')->getValue()->ofBoxWithLabel('Username');
{% endhighlight %}

This action is normally used for checking that _Remember Me_-like functionality is working, or any other circumstance where you expect the form to be partially pre-populated before the user does anything.