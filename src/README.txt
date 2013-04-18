Your src/ folder
================

This src/ folder is where you put all of your code for release.  There's
a folder for each type of file that the PEAR Installer supports.  You can
find out more about these file types online at:

http://blog.stuartherbert.com/php/2011/04/04/explaining-file-roles/

  * bin/

    If you're creating any command-line tools, this is where you'd put
    them.  Files in here get installed into /usr/bin on Linux et al.

    There is more information available here: http://blog.stuartherbert.com/php/2011/04/06/php-components-shipping-a-command-line-program/

    You can find an example here: https://github.com/stuartherbert/phix/tree/master/src/bin

  * data/

    If you have any data files (any files that aren't PHP code, and which
    don't belong in the www/ folder), this is the folder to put them in.

    There is more information available here: http://blog.stuartherbert.com/php/2011/04/11/php-components-shipping-data-files-with-your-components/

    You can find an example here: https://github.com/stuartherbert/ComponentManagerPhpLibrary/tree/master/src/data

  * php/

    This is where your component's PHP code belongs.  Everything that goes
    into this folder must be PSR0-compliant, so that it works with the
    supplied autoloader.

    There is more information available here: http://blog.stuartherbert.com/php/2011/04/05/php-components-shipping-reusable-php-code/

    You can find an example here: https://github.com/stuartherbert/ContractLib/tree/master/src/php

  * tests/functional-tests/

    Right now, this folder is just a placeholder for future functionality.
    You're welcome to make use of it yourself.

  * tests/integration-tests/

    Right now, this folder is just a placeholder for future functionality.
    You're welcome to make use of it yourself.

  * tests/unit-tests/

    This is where all of your PHPUnit tests go.

    It needs to contain _exactly_ the same folder structure as the src/php/
    folder.  For each of your PHP classes in src/php/, there should be a
    corresponding test file in test/unit-tests.

    There is more information available here: http://blog.stuartherbert.com/php/2011/08/15/php-components-shipping-unit-tests-with-your-component/

    You can find an example here: https://github.com/stuartherbert/ContractLib/tree/master/test/unit-tests

  * www/

    This folder is for any files that should be published in a web server's
    DocRoot folder.

    It's quite unusual for components to put anything in this folder, but
    it is there just in case.

    There is more information available here: http://blog.stuartherbert.com/php/2011/08/16/php-components-shipping-web-pages-with-your-components/
