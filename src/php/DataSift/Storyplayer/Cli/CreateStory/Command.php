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
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
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
 * A command to create a new story to fill in
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class CreateStory_Command extends CliCommand
{
    public function __construct()
    {
        // define the command
        $this->setName('create-story');
        $this->setShortDescription('create a new story');
        $this->setLongDescription(
            "Use this command to create a new Story.php file, complete with "
            ."the necessary PHP 'use' statement and comments to help guide you "
            ."as you bring your story to life."
            .PHP_EOL
        );
        $this->setArgsList(array(
            "namedStory.php" => "the story.php file to create"
        ));
        $this->setSwitches(array(
            new CreateStory_BasedOnSwitch,
            new CreateStory_ForceSwitch
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
            echo "*** error: you must specify which story to create\n";
            exit(1);
        }

        // we're going to be dealing with some prehistoric parts of PHP
        $legacyHandler = new Legacy_ErrorHandler();

        // create the path to the story
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

        // create the story inside the folder
        $story = <<<EOS
<?php

use Storyplayer\SPv2\Modules\Asserts;
use Storyplayer\SPv2\Modules\Checkpoint;
use Storyplayer\SPv2\Modules\Log;
use Storyplayer\SPv2\Stories\BuildStory;

EOS;

        if (isset($engine->options->basedOn)) {
            foreach ($engine->options->basedOn as $templateClass) {
                $story .= "use {$templateClass};\n";
            }
        }
        $story .= <<<EOS

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

\$story = BuildStory::newStory();
EOS;

        if (isset($engine->options->basedOn)) {
            foreach ($engine->options->basedOn as $templateClass) {
                $story .= "\n\$story->basedOn(new " . basename(str_replace('\\', '/', $templateClass)) . ");";
            }
        }

        $story .= <<<EOS

/**
 * the scenario uses Ubquitous Language to describe what you are testing
 *
 * unlike other test tools, Storyplayer does not parse your scenario in any way
 * it is only used for display purposes, to make your test self-documenting
 *
 * INSTRUCTIONS:
 *
 * pick one of the formats below that best fits your test,
 * edit to suit,
 * and delete the other scenario format(s)
 */

// this format suits a functional test
\$story->setScenario([
    // what are the pre-conditions?
    "given:",
    "- first pre-condition",
    "- second pre-condition",
    // how will the actions be performed?
    // e.g. using a browser, or an API,
    "using ...",
    // who or what is performing the actions?
    "as ...",
    // what functionality is being tested?
    "I can ...",
    "even if:",
    "- pre-condition",
    // has anything changed as a result of the actions?
    // if so, list each expected change here
    "afterwards:",
    "- XXX will exist in the database"
]);

// this format suits a test that focuses on checking for a specific consequence
// of an action (e.g. testing robustness or correctness)
\$story->setScenario([
    // what are the pre-conditions?
    "given:",
    "- first pre-condition",
    "- second pre-condition",
    // how will the actions be performed?
    // e.g. using a browser, or an API,
    "using ...",
    // who or what is performing the actions?
    "as ...",
    // what output do I expect?
    "if I ...",
    "- I get XXX", // e.g. HTTP 404
    "even if:",
    "- first pre-condition",
    // has anything changed as a result of the actions?
    // if so, list each expected change here
    "afterwards:",
    "- XXX will exist in the database"
]);

// ========================================================================
//
// TEST SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

/*
\$story->addTestSetup(function() {
    // what are we doing?
    \$log = Log::usingLog()->startAction("describe what we are doing");

    // setup the conditions for this specific test
    \$checkpoint = Checkpoint::getCheckpoint();

    // all done
    \$log->endAction();
});
*/

/*
\$story->addTestTeardown(function() {
    // what are we doing?
    \$log = Log::usingLog()->startAction("describe what we are doing");

    // undo anything that you did in addTestSetup()

    // all done
    \$log->endAction();
});
*/

// ========================================================================
//
// PRE-TEST PREDICTION
//
// ------------------------------------------------------------------------

/*
\$story->addPreTestPrediction(function() {
    // what are we doing?
    \$log = Log::usingLog()->startAction("describe what we are doing");

    // if it is okay for your story to fail, detect that here

    // all done
    \$log->endAction();
});
*/

// ========================================================================
//
// PRE-TEST INSPECTION
//
// ------------------------------------------------------------------------

/*
\$story->addPreTestInspection(function() {
    // what are we doing?
    \$log = Log::usingLog()->startAction("describe what we are doing");

    // get the checkpoint - we're going to store data in here
    \$checkpoint = Checkpoint::getCheckpoint();

    // store any data that your story is about to change, so that you
    // can do a before and after comparison

    // all done
    \$log->endAction();
});
*/

// ========================================================================
//
// ACTIONS
//
// ------------------------------------------------------------------------

/*
\$story->addAction(function() {
    // what are we doing?
    \$log = Log::usingLog()->startAction("describe what we are doing");

    // use the checkpoint to store any data collected during the action
    // this data will be examined in the postTestInspection phase
    \$checkpoint = Checkpoint::getCheckpoint();

    // this is where you perform the steps of your user story

    // all done
    \$log->endAction();
});
*/

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

\$story->addPostTestInspection(function() {
    // what are we doing?
    \$log = Log::usingLog()->startAction("describe what we are doing");

    // the information to guide our checks is in the checkpoint
    \$checkpoint = Checkpoint::getCheckpoint();

    // gather new data, and make sure that your action actually changed
    // something. never assume that the action worked just because it
    // completed to the end with no errors or exceptions!

    // all done
    \$log->endAction();
});

EOS;

        // does the file already exist?
        if (file_exists($params[0])) {
            // has the user used --force?
            if (!isset($engine->options->force) || !$engine->options->force) {
                echo "*** error: file '{$params[0]}' already exists\n";
                echo "use --force to replace this file with the new story file\n";
                exit(1);
            }
        }

        try {
            $legacyHandler->run(function() use($params, $story) {
                file_put_contents($params[0], $story);
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
