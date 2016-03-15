<?php

/**
 * Copyright (c) 2011-present Mediasift Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2016-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Cli;

use Exception;
use Phix_Project\CliEngine;
use Phix_Project\CliEngine\CliCommand;
use Phix_Project\CliEngine\CliResult;
use Phix_Project\ExceptionsLib1\Legacy_ErrorHandler;

/**
 * A command to create a new test environment to fill in
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class CreateTestEnv_Command extends CliCommand
{
    public function __construct()
    {
        // define the command
        $this->setName('create-test-env');
        $this->setShortDescription('create a new test environment file');
        $this->setLongDescription(
            "Use this command to create a new Env.php file, complete with "
            ."the necessary PHP 'use' statement and comments to help guide you "
            ."as you bring your story to life."
            .PHP_EOL
        );
        $this->setArgsList(array(
            "namedEnv.php" => "the Env.php file to create"
        ));
        $this->setSwitches(array(
            new CreateTestEnv_BasedOnSwitch,
            new CreateTestEnv_ForceSwitch
        ));
    }

    /**
     *
     * @param  CliEngine $engine
     * @param  array     $params
     * @param  mixed     $additionalContext
     * @return int
     */
    public function processCommand(CliEngine $engine, $params = array(), $additionalContext = null)
    {
        // do we have the name of the file to create?
        if (!isset($params[0])) {
            echo "*** error: you must specify which Env.php file to create\n";
            exit(1);
        }

        // we're going to be dealing with some prehistoric parts of PHP
        $legacyHandler = new Legacy_ErrorHandler();

        // create the path to the environment
        $storyFolder = dirname($params[0]);
        if (!file_exists($storyFolder)) {
            try {
                $legacyHandler->run(function() use ($storyFolder) {
                    mkdir($storyFolder, 0755, true);
                });
            }
            catch (Exception $e) {
                echo "*** error: unable to create folder '{$storyFolder}'\n";
                exit(1);
            }
        }

        // create the environment inside the folder
        $env = <<<EOS
<?php

use Storyplayer\SPv3\Modules\Asserts;
use Storyplayer\SPv3\Modules\Checkpoint;
use Storyplayer\SPv3\Modules\Log;
use Storyplayer\SPv3\Stories\BuildTestEnvironment;

EOS;

        if (isset($engine->options->basedOn)) {
            foreach ($engine->options->basedOn as $templateClass) {
                $story .= "use {$templateClass};\n";
            }
        }
        $env .= <<<EOS

// ========================================================================
//
// TEST ENVIRONMENT DETAILS
//
// ------------------------------------------------------------------------

\$env = BuildTestEnvironment::newTestEnvironment();
EOS;

        if (isset($engine->options->basedOn)) {
            foreach ($engine->options->basedOn as $templateClass) {
                $story .= "\n\$env->basedOn(new " . basename(str_replace('\\', '/', $templateClass)) . ");";
            }
        }

        $env .= <<<EOS


// ========================================================================
//
// TEST ENVIRONMENT SETUP
//
// Add one function per step. This makes it easier to debug and maintain
// your test environment construction.
//
// ------------------------------------------------------------------------

\$env->addTestEnvironmentSetup(function() {
    // what are we doing?
    \$log = Log::usingLog()->startAction("describe what we are doing");

    // add the instructions required to build the environment

    // all done
    \$log->endAction();
});

// ========================================================================
//
// TEST ENVIRONMENT TEARDOWN
//
// Add one function per step. This makes it easier to debug and maintain
// your test environment cleanup.
//
// ------------------------------------------------------------------------

\$env->addTestEnvironmentTeardown(function() {
    // what are we doing?
    \$log = Log::usingLog()->startAction("describe what we are doing");

    // undo anything that you did in addTestEnvironmentSetup()

    // all done
    \$log->endAction();
});

// ========================================================================
//
// ALL DONE
//
// Return your constructed test environment object, for Storyplayer
// to execute.
//
// ------------------------------------------------------------------------

return \$env;

EOS;

        // does the file already exist?
        if (file_exists($params[0])) {
            // has the user used --force?
            if (!isset($engine->options->force) || !$engine->options->force) {
                echo "*** error: file '{$params[0]}' already exists\n";
                echo "use --force to replace this file with the new Env.php file\n";
                exit(1);
            }
        }

        try {
            $legacyHandler->run(function() use($params, $env) {
                file_put_contents($params[0], $env);
            });
        }
        catch (Exception $e) {
            echo "*** error: " . $e->getMessage() . "\n";
            exit(1);
        }

        // all done
        return 0;
    }
}
