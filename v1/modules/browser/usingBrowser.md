---
layout: v1/modules-browser
title: usingBrowser()
prev: '<a href="../../modules/browser/expectsBrowser.html">Prev: expectsBrowser()</a>'
next: '<a href="../../modules/browser/webdriver.html">Next: The WebDriver Library</a>'
---

# usingBrowser()

_usingBrowser()_ allows you to load web pages into the browser, and to interact with the DOM elements inside the page.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\UsingBrowser_.

## Behaviour And Return Codes

Every action makes changes to the browser and the web page loaded in the browser.

* If the action succeeds, the action returns control to your code, and does not return a value.
* If the action fails, an exception is throw. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every action will be successful.

## check()

Use `$st->usingBrowser()->check()` to tick a checkbox.

{% highlight php %}
$st->usingBrowser()->check()->boxWithLabel("T's & C's");
{% endhighlight %}

## clear()

Use `$st->usingBrowser()->clear()` to clear out any values inside a form's input box.

{% highlight php %}
$st->usingBrowser()->clear()->fieldWithLabel("Username");
{% endhighlight %}

This is commonly used to remove any browser-supplied auto-complete data when filling out forms.

__See Also:__

* _[$st->usingForm()->fillOutFormFields()](../form/usingForm.html#fillOutFormFields)_

## click()

Use `$st->usingBrowser()->click()` to click on a button, link, or other element on the page.

{% highlight php %}
$st->usingBrowser()->click()->linkWithText("Login");
{% endhighlight %}

As well as being used to submit forms, we recommend that you use _click()_ to navigate around your website inside your tests.  Although it's tempting to use _gotoPage()_ all the time to jump to exactly the right spot on your website, that isn't how end-users typically get from A to B; they get there by clicking through the links on your app's pages.

If using _click()_ to get around is too slow or too much like hard work for you, you should take that as a big hint that perhaps you need to review the way you expect users to navigate around.

## gotoPage()

Use `$st->usingBrowser()->gotoPage()` to load a new page into the web browser:

{% highlight php %}
$st->usingBrowser()->gotoPage("http://datasift.com");
$st->usingBrowser()->waitForTitle(2, "Welcome To DataSift!");
{% endhighlight %}

_gotoPage()_ doesn't wait for the page to finish loading - there's no sure-fire way to detect that via WebDriver at this time.  We've found that waiting for the page's title to change is reliable.

We recommend that you use _gotoPage()_ the first time your story needs to load a web page, and then use _[click()](#click)_ after that to navigate around your app.  This mimics the behaviour (and the experience) of your end users.

## select()

Use `$st->usingBrowser()->select()` to pick an option in a dropdown list.

{% highlight php %}
$st->usingBrowser()->select("United Kingdom")->fromDropdownWithLabel("Country");
{% endhighlight %}

_select()_ takes one parameter - the text of the option that you want to select.

In general, we recommend using _[$st->usingForm()->select()](../form/usingForm.html#select)_ instead, because the Form module restricts its operations to an identified form.  This can avoid problems on pages where there are multiple versions of a form (e.g. a combined registration / login page where each form is on a different tab).

## type()

Use `$st->usingBrowser()->type()` to send a string of text to a selected DOM element.

{% highlight php %}
$st->usingBrowser()->type("Storyplayer lives!")->intoFieldWithLabel("progress");
{% endhighlight %}

You can also use _type()_ to send a mixture of normal text and non-printing keys, using the constants defined in _DataSift\WebDriver\WebDriverKeys_:

{% highlight php %}
use DataSift\WebDriver\WebDriverKeys;

$st->usingBrowser()->type(
	"class ExampleClass {"
	. WebDriverKeys::RETURN_KEY
	. WebDriverKeys::TAB_KEY
	. "indented text!"
	. WebDriverKeys::RETURN_KEY
	. "}"
))->intoFieldWithLabel("text-editor");
{% endhighlight %}

Selenium's [Json Wire Protocol](https://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/value) defines the following non-printing keys, available as constants in the helper class _DataSift\WebDriver\WebDriverKey_:

* `WebDriverKey::NULL_KEY` (resets any held-down modifer keys)
* `WebDriverKey::CANCEL_KEY`
* `WebDriverKey::HELP_KEY`
* `WebDriverKey::BACKSPACE_KEY`
* `WebDriverKey::TAB_KEY`
* `WebDriverKey::CLEAR_KEY`
* `WebDriverKey::RETURN_KEY`
* `WebDriverKey::ENTER_KEY`
* `WebDriverKey::SHIFT_KEY`
* `WebDriverKey::CONTROL_KEY`
* `WebDriverKey::ALT_KEY`
* `WebDriverKey::PAUSE_KEY`
* `WebDriverKey::ESC_KEY`
* `WebDriverKey::SPACE_KEY`
* `WebDriverKey::PGUP_KEY`
* `WebDriverKey::PGDN_KEY`
* `WebDriverKey::END_KEY`
* `WebDriverKey::HOME_KEY`
* `WebDriverKey::LEFT_ARROW_KEY`
* `WebDriverKey::UP_ARROW_KEY`
* `WebDriverKey::RIGHT_ARROW_KEY`
* `WebDriverKey::DOWN_ARROW_KEY`
* `WebDriverKey::INSERT_KEY`
* `WebDriverKey::DELETE_KEY`
* `WebDriverKey::SEMICOLON_KEY`
* `WebDriverKey::EQUALS_KEY`
* `WebDriverKey::NUMPAD_0_KEY`
* `WebDriverKey::NUMPAD_1_KEY`
* `WebDriverKey::NUMPAD_2_KEY`
* `WebDriverKey::NUMPAD_3_KEY`
* `WebDriverKey::NUMPAD_4_KEY`
* `WebDriverKey::NUMPAD_5_KEY`
* `WebDriverKey::NUMPAD_6_KEY`
* `WebDriverKey::NUMPAD_7_KEY`
* `WebDriverKey::NUMPAD_8_KEY`
* `WebDriverKey::NUMPAD_9_KEY`
* `WebDriverKey::NUMPAD_MULTIPLY_KEY`
* `WebDriverKey::NUMPAD_ADD_KEY`
* `WebDriverKey::SEPARATOR_KEY`
* `WebDriverKey::NUMPAD_SUBTRACT_KEY`
* `WebDriverKey::NUMPAD_DECIMAL_KEY`
* `WebDriverKey::NUMPAD_DIVIDE_KEY`
* `WebDriverKey::F1_KEY`
* `WebDriverKey::F2_KEY`
* `WebDriverKey::F3_KEY`
* `WebDriverKey::F4_KEY`
* `WebDriverKey::F5_KEY`
* `WebDriverKey::F6_KEY`
* `WebDriverKey::F7_KEY`
* `WebDriverKey::F8_KEY`
* `WebDriverKey::F9_KEY`
* `WebDriverKey::F10_KEY`
* `WebDriverKey::F11_KEY`
* `WebDriverKey::F12_KEY`
* `WebDriverKey::META_KEY` (the Windows key on Microsoft keyboards, the Command key on Apple keyboards)

Modifier keys (`WebDriverKey::SHIFT_KEY`, `WebDriverKey::CONTROL_KEY`, `WebDriverKey::ALT_KEY` and `WebDriverKey::META_KEY`) are _sticky_ - send them once to hold the key down, send them a second time to release the key.

After all of the keys have been typed into the browser, the modifier keys are reset to their default state of not-pressed.

## waitForOverlay()

Use `$st->usingBrowser()->waitForOverlay()` to wait for an overlay (such as an image lightbox) to appear on the current page in the web browser.

{% highlight php %}
$st->usingBrowser()->waitForOverlay(2, 'lightbox');
{% endhighlight php %}

_waitForOverlay()_ takes two parameters:

* `$timeout` - how many seconds to wait before the action fails
* `$id` - the _id_ attribute of the overlay that should appear

## waitForTitle()

Use `$st->usingBrowser()->waitForTitle()` to wait for the page title to change.

{% highlight php %}
$st->usingBrowser()->gotoPage("http://datasift.com");
$st->usingBrowser()->waitForTitle(2, "Welcome To DataSift!");
{% endhighlight %}

_waitForTitle()_ takes two parameters:

* `$timeout` - how many seconds to wait before deciding that the action has failed
* `$title` - the page title that you expect

See _[gotoPage()](#gotopage)_ for a fuller discussion.

## waitForTitles()

Use `$st->usingBrowser()->waitForTitles()` to wait for the page title to change.

{% highlight php %}
$st->usingBrowser()->gotoPage("http://datasift.com");
$st->usingBrowser()->waitForTitles(2, array(
	"Personal Details",
	"Payment Amount"
));
{% endhighlight %}

_waitForTitles()_ takes two parameters:

* `$timeout` - how many seconds to wait before deciding that the action has failed
* `$title` - a list of the different page titles that you expect

See _[gotoPage()](#gotopage)_ for a fuller discussion.
