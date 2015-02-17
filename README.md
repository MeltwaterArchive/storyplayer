# Storyplayer

Bring your user and service stories to life through your tests.

## Installation

Storyplayer should be installed via [Composer](http://getcomposer.org/). To install Storyplayer, create a `composer.json` file with the following contents:

```json
{
    "require": {
        "datasift/storyplayer": "~2.0.0"
    }
}
```

Then, run `composer install`. Once that's completed, run `./vendor/bin/storyplayer install` to install any additional dependencies.

## Usage

If your tests need a web browser, make sure you've started browsermob-proxy and selenium:

```
./vendor/bin/browsermob-proxy.sh start
./vendor/bin/selenium-server.sh start
```

Basic usage is:

```
./vendor/bin/storyplayer <story>
```

where:

* `<story>` is the path to the PHP file containing your story and its test

## Full Documentation

You'll find our docs at [http://datasift.github.io/storyplayer](http://datasift.github.io/storyplayer/). They're currently a work-in-progress.

## Contributing

Contributions are most welcome.

1. Fork on GitHub
2. Create a feature branch
3. Commit your changes __with tests__
4. New feature? Send a pull request against the `develop` branch.
5. Bug fix? Send a pull request against the `master` branch.

Please don't break backwards-compatibility :)

## License

New BSD license.  Full details are in the LICENSE.txt file.
