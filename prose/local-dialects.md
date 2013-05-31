---
layout: prose
title: Local Dialects
prev: '<a href="../prose/global-dialect.html">Prev: The Global Dialect</a>'
next: '<a href="../prose/creating-prose-modules.html">Next: Creating Your Own Prose Modules</a>'
---

# Local Dialects

_Local dialects_ are your Prose modules, the reusable modules that you write for your unique needs.

Your _storyteller.json_ file should include the following configuration:

{% highlight json %}
{
	"local-dialects": [
		"path/to/your/PSR0/tree"
	]
}
{% endhighlight %}

We'll expand this section shortly with much better details about how this works.