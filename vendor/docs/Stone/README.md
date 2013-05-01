# ms-lib-stone

**ms-lib-stone** is DataSift's in-house framework for [Storyplayer](http://datasift.github.io/storyplayer), Storyteller, Hornet and other QA tools.

## System-Wide Installation

ms-lib-stone should be installed using the [PEAR Installer](http://pear.php.net). This installer is the PHP community's de-facto standard for installing PHP components.

    sudo pear channel-discover datasift.github.io/pear
    sudo pear install --alldeps datasift/ms-lib-stone

Composer support is coming soon.

## As A Dependency On Your Component

If you are creating a component that relies on ms-lib-stone, please make sure that you add ms-lib-stone to your component's package.xml file:

```xml
<dependencies>
  <required>
    <package>
      <name>ms-lib-stone</name>
      <channel>datasift.github.io/pear</channel>
      <min>1.0.0</min>
      <max>1.999.99999</max>
    </package>
  </required>
</dependencies>
```

Usage
-----

At the moment, we haven't published any documentation for this library.  Please look at the unit tests to see how we recommend using the library.

Development Environment
-----------------------

If you want to patch or enhance this component, you will need to create a suitable development environment. The easiest way to do that is to install phix4componentdev:

    # phix4componentdev
    sudo apt-get install php5-xdebug
    sudo apt-get install php5-imagick
    sudo pear channel-discover pear.phix-project.org
    sudo pear -D auto_discover=1 install -Ba phix/phix4componentdev

You can then fork and clone the git repository.

Then, install a local copy of this component's dependencies to complete the development environment:

    # build vendor/ folder
    phing build-vendor

To make life easier for you, common tasks (such as running unit tests, generating code review analytics, and creating the PEAR package) have been automated using [phing](http://phing.info).  You'll find the automated steps inside the build.xml file that ships with the component.

Run the command 'phing' in the component's top-level folder to see the full list of available automated tasks.

License
-------

See LICENSE.txt for full license details.
