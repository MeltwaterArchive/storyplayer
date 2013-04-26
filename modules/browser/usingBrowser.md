---
layout: modules-browser
title: usingBrowser()
prev: '<a href="../../modules/browser/expectsBrowser.html">Prev: expectsBrowser()</a>'
next: '<a href="../../modules/browser/webdriver.html">Next: The WebDriver Library</a>'
---

# usingBrowser()

_usingBrowser()_ allows you to load web pages into the browser, and to interact with the DOM elements inside the page.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\BrowserActions_.

## Behaviour And Return Codes

Every action makes changes to the browser and the web page loaded in the browser.

* If the action succeeds, the action returns control to your code, and does not return a value.
* If the action fails, an exception is throw. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every action will be successful.

## check()

Use _$st->usingBrowser()->check()_ to tick a checkbox.

{% highlight php %}
$st->usingBrowser()->check()->boxWithLabel("T's & C's");
{% endhighlight %}

## clear()

Use _$st->usingBrowser()->clear()_ to clear out any values inside a form's input box.

{% highlight php %}
$st->usingBrowser()->clear()->fieldWithLabel("Username");
{% endhighlight %}

This is commonly used to remove any browser-supplied auto-complete data when filling out forms.

__See Also:__

* _[$st->usingForm()->fillOutFormFields()](../form/usingForm.html#fillOutFormFields)_

## click()

Use _$st->usingBrowser()->click()_ to click on a button, link, or other element on the page.

{% highlight php %}
$st->usingBrowser()->click()->linkWithText("Login");
{% endhighlight %}

As well as being used to submit forms, we recommend that you use _click()_ to navigate around your website inside your tests.  Although it's tempting to use _gotoPage()_ all the time to jump to exactly the right spot on your website, that isn't how end-users typically get from A to B; they get there by clicking through the links on your app's pages.

If using _click()_ to get around is too slow or too much like hard work for you, you should take that as a big hint that perhaps you need to review the way you expect users to navigate around.

## gotoPage()

Use _$st->usingBrowser()->gotoPage()_ to load a new page into the web browser:

{% highlight php %}
$st->usingBrowser()->gotoPage("http://datasift.com");
$st->usingBrowser()->waitForTitle(2, "Welcome To DataSift!");
{% endhighlight %}

_gotoPage()_ doesn't wait for the page to finish loading - there's no sure-fire way to detect that via WebDriver at this time.  We've found that waiting for the page's title to change is reliable.

We recommend that you use _gotoPage()_ the first time your story needs to load a web page, and then use _[click()](#click)_ after that to navigate around your app.  This mimics the behaviour (and the experience) of your end users.

## select()

Use _$st->usingBrowser()->select()_ to pick an option in a dropdown list.

{% highlight php %}
$st->usingBrowser()->select("United Kingdom")->fromDropdownWithLabel("Country");
{% endhighlight %}

_select()_ takes one parameter - the text of the option that you want to select.

In general, we recommend using _[$st->usingForm()->select()](../form/usingForm.html#select)_ instead, because the Form module restricts its operations to an identified form.  This can avoid problems on pages where there are multiple versions of a form (e.g. a combined registration / login page where each form is on a different tab).

## type()

## typeSpecial()

## waitForOverlay()

## waitForTitle()

## waitForTitles()