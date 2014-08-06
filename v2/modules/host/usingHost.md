---
layout: v2/modules-host
title: usingHost()
prev: '<a href="../../modules/host/expectsHost.html">Prev: expectsHost()</a>'
next: '<a href="../../modules/hoststable/index.html">Next: The HostsTable Module</a>'
---

# usingHost()

_usingHost()_ allows you to run commands on the named host.

The source code for these actions can be found in the class _DataSift\Storyplayer\Prose\UsingHost_.

## Behaviour And Return Codes

If the command runs, control is returned to your code, and a _CommandResult_ object is returned.

If the command fails, an exception is thrown. Do not catch exceptions thrown by these actions. Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## runCommand()

Use `$st->usingHost()->runCommand()` to run a command on the host.

{% highlight php %}
$result = $st->usingHost($hostName)->runCommand($command);
{% endhighlight %}

where:

* `$hostName` is the name you set when you created the host
* `$command` is the command to execute
* `$result` is a _CommandResult_ object containing the _returnCode_ and the _output_

__NOTE:__

If the return code from running `$command` is not zero, the command is assumed to have failed, and an exception is thrown.  This can be problematic, as many modern tools don't follow the correct UNIX standards for return codes.  You can use _[usingHost()->runCommandAndIgnoreErrors()](#runcommandandignoreerrors)_ instead to get around this.

## runCommandAndIgnoreErrors()

Use `$st->usingHost()->runCommandAndIgnoreErrors()` to run a command on the host.

{% highlight php %}
$result = $st->usingHost($hostName)->runCommandAndIgnoreErrors($command);
{% endhighlight %}

where:

* `$hostName` is the name you set when you created the host
* `$command` is the command to execute
* `$result` is a _CommandResult_ object containing the _returnCode_ and the _output_

__NOTE:__

This action does not throw an exception if the return code from running `$command` is not zero.  This allows you to work with badly-behaved commands and tools.

## runCommandAsUser()

Use `$st->usingHost()->runCommandAsUser()` to run a command on the host as a specific user.

{% highlight php %}
$result = $st->usingHost($hostName)->runCommandAsUser($command, $user);
{% endhighlight %}

where:

* `$hostName` is the name you set when you created the host
* `$command` is the command to execute
* `$user` is the user you want to run the command as
* `$result` is a _CommandResult_ object containing the _returnCode_ and the _output_

Whenever a host is created, details about the host are added to Storyplayer's [hosts table](../hoststable/how-hosts-are-remembered.html). The host's details normally include a default SSH user to use (e.g. the _vagrant_ user for Vagrant VMs).  Sometimes (e.g. when creating virtual machine images on Amazon EC2) it's useful to be able to override this default SSH user on a one-off basis.

__NOTE:__

If the return code from running `$command` is not zero, the command is assumed to have failed, and an exception is thrown.  This can be problematic, as many modern tools don't follow the correct UNIX standards for return codes.  You can use _[usingHost()->runCommandAsUserAndIgnoreErrors()](#runcommandasuserandignoreerrors)_ instead to get around this.

## runCommandAsUserAndIgnoreErrors()

Use `$st->usingHost()->runCommandAsUserAndIgnoreErrors()` to run a command on the host as a specific user.

{% highlight php %}
$result = $st->usingHost($hostName)->runCommandAsUser($command, $user);
{% endhighlight %}

where:

* `$hostName` is the name you set when you created the host
* `$command` is the command to execute
* `$user` is the user you want to run the command as
* `$result` is a _CommandResult_ object containing the _returnCode_ and the _output_

Whenever a host is created, details about the host are added to Storyplayer's [hosts table](../hoststable/how-hosts-are-remembered.html). The host's details normally include a default SSH user to use (e.g. the _vagrant_ user for Vagrant VMs).  Sometimes (e.g. when creating virtual machine images on Amazon EC2) it's useful to be able to override this default SSH user on a one-off basis.

__NOTE:__

This action does not throw an exception if the return code from running `$command` is not zero.  This allows you to work with badly-behaved commands and tools.