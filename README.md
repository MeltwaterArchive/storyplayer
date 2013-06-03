# Storyplayer

Bring your user and service stories to life through your tests.

## If Installed System-Wide ...

### Installation

Storyplayer should be installed using the [PEAR Installer](http://pear.php.net).

	sudo pear config-set auto_discover 1
    sudo pear channel-discover datasift.github.io/pear
    sudo pear install --alldeps datasift/storyplayer

### Usage

If your tests need a web browser, make sure you've started browsermob-proxy and selenium:

```
browsermob-proxy.sh start
selenium-server.sh start
```

Basic usage is:

```
storyplayer <environment> <story>
```

where:

* <environment> is the name of the environment that you want to run your story against
* <story> is the path to the PHP file containing your story and its test

## Running It Out Of A Git Clone

See [installing Storyplayer](http://datasift.github.io/storyplayer/configuration.html).

### Building The Dependencies

If you want to run storyplayer from inside its own git repo, make sure that you have [Phix](http://phix-project.org) installed, and then do the following:

```
phing build-vendor
```

### Usage

If your tests need a web browser, make sure you've started browsermob-proxy and selenium:

```
vendor/bin/browsermob-proxy.sh start
vendor/bin/selenium-server.sh start
```

Basic usage is:

```
storyplayer self-test src/tests/functional-tests/<story>
```

where:

* __self-test__ is the name of an environment already defined in __storyplayer.xml.dist__
* src/tests/functional-tests/<story> is the path to Storyplayer's own tests, that ship with it :)

## Full Documentation

You'll find our docs at [http://datasift.github.io/storyplayer](http://datasift.github.io/storyplayer/).

## License

New BSD license.  Full details are in the LICENSE.txt file.
