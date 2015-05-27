---
layout: v2/using-stories
title: Test Users
prev: '<a href="../../using/stories/story-params.html">Prev: Story Parameters</a>'
next: '<a href="../../using/storyplayer-commands/index.html">Next: Storyplayer Commands</a>'
updated_for_v2: true
---

# Test Users

The vast majority of apps these days involve registering new users, logging in / authenticating as an existing user, and the things that different users (and different types of user) can and cannot do.

You can create a JSON file containing the details about your users, and pass that into your tests. When your tests have finished, Storyplayer will save any updated details back to your file for you.

## Defining Test Users

Test users go into a JSON file in your project:

{% highlight json %}
{
    "user1": {
        // user 1's settings
    },
    "user2": {
        // user 2's settings
    }
}
{% endhighlight %}

where:

* __user1__, __user2__ and so on are unique IDs for the user

## Passing Test Users Into Your Test

Use the `--users` switch to tell Storyplayer where your test users file is:

{% highlight bash %}
vendor/bin/storyplayer --users=./path/to/users.json
{% endhighlight %}

## Accessing Test Users From Your Test

Use the [Users module](../../modules/users/index.html) to access your test users:

{% highlight php startinline %}
$story->addAction(function() {
    // $adminUser becomes a PHP object
    $adminUser = fromUsers()->getUser('user1');

    // log in as user
    usingBrowser()->gotoPage("http://my-app.local/login");
    usingForm('login')->fillInFormFields([
        'username' => $adminUser->username,
        'password' => $adminuser->password,
    ]);
    usingForm('login')->clickButtonWithText('Login');
});
{% endhighlight %}

## Saving Changes To Your Test Users

To updated the details of your test user, simply edit your user inside your test. Storyplayer will automatically save these changes back to disk when your tests have completed.

{% highlight php startinline %}
$story->addAction(function () {
    $adminUser = fromUsers()->getUser('user1');

    $newPassword = 'some random password';

    usingBrowser()->gotoPage("http://my-app.local/change-password");
    usingForm('change-password')->fillInFormFields([
        'New Password' => $newPassword
    ]);
    usingForm('change-password')->clickButtonWithText('Change Password');

    // if we get here, then we know the password changed on the server
    $adminUser->password = $newPassword;
});
{% endhighlight %}

## Telling Storyplayer Not To Save Changes

By default, if you change your test users, Storyplayer will save those changes to disk when your tests have completed.  If you don't want your changes saved, use the `--read-only-users` command-line switch:

{% highlight bash %}
vendor/bin/storyplayer --read-only-users --users=./path-to-file.json
{% endhighlight bash %}