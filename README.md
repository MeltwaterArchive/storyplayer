# Storyplayer

Bring your user and service stories to life through your tests.

## If Installed System-Wide ...

### Installation

Storyplayer should be installed via [Composer](http://getcomposer.org/). To install Storyplayer, create a `composer.json` file with the following contents:

```json
{
    "require": {
        "datasift/storyplayer": "1.5.0"
    }
}
```

Then, run `composer install`. Once that's completed, run `./vendor/bin/storyplayer install` to install any additional dependencies.

### Usage

If your tests need a web browser, make sure you've started browsermob-proxy and selenium:

```
./vendor/bin/browsermob-proxy.sh start
./vendor/bin/selenium-server.sh start
```

Basic usage is:

```
./vendor/bin/storyplayer [-e <environment>] <story>
```

where:

* `<environment>` is the name of the environment that you want to run your story against (defaults to your hostname)
* `<story>` is the path to the PHP file containing your story and its test

## Running It Out Of A Git Clone

See [installing Storyplayer](http://datasift.github.io/storyplayer/installation.html).

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

## Common issues

If you try and run Storyplayer but get one of the following error messages, the OS you're using isn't currently supported:

* fatal error: Unable to detect OS
* fatal error: Unable to create from distributions: [distribution list]

Currently supported OS's are:

* OSX
* Ubuntu
* Linux Mint
* Fedora
* CentOS 5
* CentOS 6

If you're not using one of these but still want to use Storyplayer, we can help! To enable us to add support, open an issue and post the results of the following commands:

```bash
# This is how we detect the OS
cat /etc/issue

# This is how we get your IP address
ifconfig
```

With this information, we can add support for your OS to Storyplayer.

## License

New BSD license.  Full details are in the LICENSE.txt file.
