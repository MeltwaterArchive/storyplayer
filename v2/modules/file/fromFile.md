---
layout: v2/modules-file
title: fromFile()
prev: '<a href="../../modules/file/index.html">Prev: The File Module</a>'
next: '<a href="../../modules/file/usingFile.html">Next: usingFile()</a>'
updated_for_v2: true
---

# fromFile()

_fromFile()_ allows you to create unique names for temporary files.

The source code for these actions can be found in the class `Prose\FromFile`.

## Behaviour And Return Codes

Every action returns either a value on success, or `NULL` on failure.  None of these actions throw exceptions on failure.

## getTmpFileName()

Use `fromFile()->getTmpFileName()` generate a unique temporary filename for use with other modules.

{% highlight php startinline %}
$tmpName = fromFile()->getTmpFileName();
{% endhighlight %}
