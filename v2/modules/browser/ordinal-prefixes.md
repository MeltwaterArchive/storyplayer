---
layout: v2/modules-browser
title: Supported Ordinal Prefixes
updated_for_v2: true
prev: '<a href="../../modules/browser/searching-the-dom.html">Prev: Searching The DOM</a>'
next: '<a href="../../modules/browser/search-targets.html">Next: Supported Search Targets</a>'
---

# Supported Ordinal Prefixes

_Ordinal prefixes_ are used to control exactly how many DOM elements the search term should return. To make your code as natural as possible to read and write, we support two different styles of prefix: _first, second, ..._ and _one, two, ..._.

## Why Do We Need Ordinal Prefixes?

Imagine that you're on the phone to someone, and you're talking them through using a web site. If you're anything like me, you'll probably say things like:

* click "Home"
* type "200" where it says "amount"
* click "Next"

What happens when the person you're talking to replies, "There are three _Nexts_. Which one should I click?"

You might reply, "click the second one." And that's exactly what _ordinal prefixes_ let you do inside _search terms_.

## Nth-Style

Use _first, second, third ... nineteenth, or twentieth_ to do something to a single, specific element on the HTML page.

For example, to click the fourth link on the page that says "Next", you would do this:

{% highlight php startinline %}
usingBrowser()->click()->fourthLinkWithText("Next");
{% endhighlight %}

<div class="callout info" markdown="1">
#### Use nth-style Prefixes To Pick One Element From Many

This style of ordinal prefix can be used _anywhere_ that you're searching for a single element in the HTML page.
</div>
<div class="callout info" markdown="1">
#### Nth-style Goes All The Way Up To Twentieth

You can use anything from _first_ all the way up to _twentieth_. If you really do need this to go higher for some reason, please open an issue on Github.
</div>
<div class="callout info" markdown="1">
#### Nth-style Is Optional

If you don't use an _nth-style_ ordinal prefix, internally Storyplayer will assume that you want the first DOM element that matches your search term.

This seems to be what someone would naturally expect to happen, and preserves backwards compatibility for stories written before we added ordinal prefixes in Storyplayer 2.2.0.
</div>
<div class="callout info" markdown="1">
#### How Visibility Affects Results

Just because an element exists in the DOM, it doesn't mean that you can access it via Storyplayer. If the element isn't visible, then unfortunately Storyplayer simply can't use it.

Why? This is because Selenium WebDriver has a deep-rooted philosophy of trying to be as close to a human user as possible. If a human was looking at your web page in their browser, they wouldn't be able to click on a link that they can't see because it is hidden in some way. If a real person can't click the link, it doesn't make sense that Storyplayer will let your test click the link.

When Storyplayer is finding your Nth element, the search goes like this:

1. Find all the elements that match your search term
1. From that list, pick the Nth element that Selenium WebDriver confirms is currently visible

This will allow you to return input fields with the 'hidden' attribute set. Just don't try clicking them or typing into them!
</div>

## N-Style

Use _one, two, three ... nineteen, or twenty_ when you are searching for a group of elements from the HTML page.

For example, to make sure that the page has two links that say "Logout", you would do this:

{% highlight php startinline %}
expectsBrowser()->has()->twoLinksWithText("Logout");
{% endhighlight %}

Or, to make sure that the page has no links that say "Logout", you would do this:

{% highlight php startinline %}
expectsBrowser()->doesntHave()->anyLinksWithText("Logout");
{% endhighlight %}
<div class="callout info" markdown="1">
#### N-Style Is Optional

If you don't use an _n-style_ ordinal prefix, internally Storyplayer will assume that you want all of the DOM elements that match your search term.

This preserves backwards compatibility with stories written before we added ordinal prefixes in Storyplayer 2.2.0.0
</div>

<div class="callout info" markdown="1">
#### Other Words You Can Use

As well as _one, two_ and so on, you can also use these words as an _n-style_ ordinal prefix:

* __no__: means 'zero'
* __any__: means 'zero' or 'not zero' depending on the context
* __an / a__: means 'one'
* __several__: means more than two and less than ten
</div>