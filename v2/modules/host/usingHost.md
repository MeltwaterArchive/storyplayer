---
layout: v2/modules-host
title: usingHost()
prev: '<a href="../../modules/host/expectsHost.html">Prev: expectsHost()</a>'
next: '<a href="../../modules/http/index.html">Next: The HTTP Module</a>'
updated_for_v2: true
---

# usingHost()

_usingHost()_ allows you to run commands on the given host.

The source code for these actions can be found in the class `Prose\UsingHost`.

## Behaviour And Return Codes

If the command runs, control is returned to your code, and a _CommandResult_ object is returned.

If the command fails, an exception is thrown. Do not catch exceptions thrown by these actions. Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## runCommand()

Use `usingHost()->runCommand()` to run a command on the host.

{% highlight php startinline %}
$result = usingHost($hostId)->runCommand($command);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$command` is the command to execute
* `$result` is a _CommandResult_ object containing the _returnCode_ and the _output_

__NOTE:__

If the return code from running `$command` is not zero, the command is assumed to have failed, and an exception is thrown.  This can be problematic, as many modern tools don't follow the correct UNIX standards for return codes.  You can use _[usingHost()->runCommandAndIgnoreErrors()](#runcommandandignoreerrors)_ instead to get around this.

## runCommandAndIgnoreErrors()

Use `usingHost()->runCommandAndIgnoreErrors()` to run a command on the host.

{% highlight php startinline %}
$result = usingHost($hostId)->runCommandAndIgnoreErrors($command);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$command` is the command to execute
* `$result` is a _CommandResult_ object containing the _returnCode_ and the _output_

__NOTE:__

This action does not throw an exception if the return code from running `$command` is not zero.  This allows you to work with badly-behaved commands and tools.

## runCommandAsUser()

Use `usingHost()->runCommandAsUser()` to run a command on the host as a specific user.

{% highlight php startinline %}
$result = usingHost($hostId)->runCommandAsUser($command, $user);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$command` is the command to execute
* `$user` is the user you want to run the command as
* `$result` is a _CommandResult_ object containing the _returnCode_ and the _output_

Whenever a host is created, details about the host are added to Storyplayer's [hosts table](../hoststable/how-hosts-are-remembered.html). The host's details normally include a default SSH user to use (e.g. the _vagrant_ user for Vagrant VMs).  Sometimes (e.g. when creating virtual machine images on Amazon EC2) it's useful to be able to override this default SSH user on a one-off basis.

__NOTE:__

If the return code from running `$command` is not zero, the command is assumed to have failed, and an exception is thrown.  This can be problematic, as many modern tools don't follow the correct UNIX standards for return codes.  You can use _[usingHost()->runCommandAsUserAndIgnoreErrors()](#runcommandasuserandignoreerrors)_ instead to get around this.

## runCommandAsUserAndIgnoreErrors()

Use `usingHost()->runCommandAsUserAndIgnoreErrors()` to run a command on the host as a specific user.

{% highlight php startinline %}
$result = usingHost($hostId)->runCommandAsUser($command, $user);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$command` is the command to execute
* `$user` is the user you want to run the command as
* `$result` is a _CommandResult_ object containing the _returnCode_ and the _output_

Whenever a host is created, details about the host are added to Storyplayer's [hosts table](../hoststable/how-hosts-are-remembered.html). The host's details normally include a default SSH user to use (e.g. the _vagrant_ user for Vagrant VMs).  Sometimes (e.g. when creating virtual machine images on Amazon EC2) it's useful to be able to override this default SSH user on a one-off basis.

__NOTE:__

This action does not throw an exception if the return code from running `$command` is not zero.  This allows you to work with badly-behaved commands and tools.

## startInScreen()

Use `usingHost()->startInScreen()` to run a command in a `screen` session on the host.

{% highlight php startinline %}
usingHost($hostId)->startInScreen($sessionName, $commandLine);
{% endhighlight %}

* `$hostId` is the ID of the host in your test environment
* `$sessionName` is name to give to this session
* `$commandLine` is the command to execute inside `screen`

## stopInScreen()

Use `usingHost()->stopInScreen()` to stop a `screen` session on the host.

{% highlight php startinline %}
usingHost($hostId)->stopInScreen($sessionName);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$sessionName` is the name you used when you started the session

__NOTE:__

* Starting from Storyplayer v2.2.0, you can use this action to stop any screen session, not just screen sessions started from within Storyplayer.

## stopAllScreens()

Use `usingHost()->stopAllScreens()` to stop all screen sessions on the given host.

{% highlight php startinline %}
usingHost($hostId)->stopAllScreens();
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment

## stopProcess()

Use `usingHost()->stopProcess()` to stop a running process on the given host.

{% highlight php startinline %}
usingHost($hostId)->stopProcess($pid, $grace = 5);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$pid` is the process ID that you want to stop
* `$grace` is how long to wait before killing the process (the default is 5 seconds)

__NOTES:__

* we send SIGTERM first. If the process is still running after `$grace` seconds, we follow up with SIGKILL.

## uploadFile()

Use `usingHost()->uploadFile()` to upload a file from your computer to the given host.

{% highlight php startinline %}
usingHost($hostId)->uploadFile($src, $dest);
{% endhighlight %}

where:

* `$hostId` is the ID of the host in your test environment
* `$src` is the file on your computer to upload
* `$dest` is where the file should be uploaded to on the remote host