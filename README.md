# Storyplayer

Bring your user and service stories to life through your tests.

## System-Wide Installation

Storyplayer should be installed using the [PEAR Installer](http://pear.php.net).

	sudo pear config-set auto_discover 1
    sudo pear channel-discover datasift.github.io/pear
    sudo pear install --alldeps datasift/storyteller

## Usage

Basic usage is:

```
storyplayer <environment> <story>
```

where:

* <environment> is the name of the environment that you want to run your story against
* <story> is the path to the PHP file containing your story and its test

You'll find our docs at [http://datasift.github.io/storyplayer](http://datasift.github.io/storyplayer/).

## License

New BSD license.  Full details are in the LICENSE.txt file.
