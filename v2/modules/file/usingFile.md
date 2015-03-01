---
layout: v2/modules-file
title: usingFile()
prev: '<a href="../../modules/file/fromFile.html">Prev: fromFile()</a>'
next: '<a href="../../modules/form/index.html">Next: The Form Module</a>'
---

# usingFile()

_usingFile()_ allows you to remove files from the computer that Storyplayer is running on.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\UsingFile_.

## Behaviour And Return Codes

Every action makes changes to the file that you specify.

* If the action succeeds, the action returns control to your code, and does not return a value.
* If the action fails, an exception is throw. _Do not catch exceptions thrown by these actions._ Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every action will be successful.

## removeFile()

Use `usingFile()->removeFile()` to delete a file off disk.

{% highlight php %}
$tmpName = usingFile()->getTmpFileName();
// do something with the temporary file here
usingFile()->removeFile($tmpName);
{% endhighlight %}
