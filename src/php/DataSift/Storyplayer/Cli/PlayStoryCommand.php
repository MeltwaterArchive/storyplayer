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

use Phix_Project\CliEngine;
use Phix_Project\CliEngine\CliCommand;
use Phix_Project\CliEngine\CliEngineSwitch;
use Phix_Project\CliEngine\CliResult;

use DataSift\Stone\LogLib\Log;
use DataSift\Storyplayer\PlayerLib\StoryPlayer;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\StoryLib\StoryLoader;
use DataSift\Storyplayer\StoryListLib\StoryListLoader;
use DataSift\Storyplayer\UserLib\User;
use DataSift\Storyplayer\UserLib\GenericUserGenerator;
use DataSift\Storyplayer\UserLib\ConfigUserLoader;

/**
 * A command to play a story, or a list of stories
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class PlayStoryCommand extends CliCommand
{
	public function __construct()
	{
		// define the command
		$this->setName('play-story');
		$this->setShortDescription('play a story, or a list of stories');
		$this->setLongDescription(
			"Use this command to get a list of all of the test environments"
			. " that are defined in the config files."
			.PHP_EOL
		);
		$this->setArgsList(array(
			"[<story|list>]" => "run a story, or a list of stories"
		));
	}

	public function processCommand(CliEngine $engine, $params = array(), $additionalContext = null)
	{
		// shorthand
		$envList              = $additionalContext->envList;
		$runtimeConfig        = $additionalContext->runtimeConfig;
		$runtimeConfigManager = $additionalContext->runtimeConfigManager;
		$staticConfig         = $additionalContext->staticConfig;
		$staticConfigManager  = $additionalContext->staticConfigManager;

		// do we have a story, or list of stories?
		if (!isset($params[0])) {
			echo "*** error: you must specify which story to play\n";
			return 1;
		}

		// which environment are we using?
		if (!isset($engine->options->environment)) {
			// this switch is optional ... *if* there is only one environment
			// in the list
			if (count($envList) > 1) {
				echo "*** error: there is more than one test environment defined\n\n";
				echo "use 'storyplayer list-environments' to see the list\n";
				echo "use '-e <environment>' to select one\n";
				return 1;
			}

			// we will use the first (and only) environment in the list
			$envName = $envList[0];
		}
		else {
			// use the environment that the user has provided
			$envName = $engine->options->environment;
		}

		// setup logging
		Log::init("storyplayer", $staticConfig->logger);

		// do we need to load environment-specific config?
		if (!isset($staticConfig->environments, $staticConfig->environments->$envName))
		{
			// load our environment-specific config
			//
			// this will be merged in with the default config
			$staticConfigManager->loadAdditionalConfig($staticConfig, $envName);
		}

		// create our user generator
		$userGenerator = new GenericUserGenerator();

		// create our user loader
		// it will use our user generator if no cached user is found
		$userLoader = new ConfigUserLoader($userGenerator);

		// are we loading a story, or a list of stories?
		$arg2suffix = end(explode('.', $params[0]));
		switch($arg2suffix)
		{
			case "php":
				// we are running an individual story

				// load our story
				$story = StoryLoader::loadStory($params[0]);

				// create something to play this story
			    $player = new StoryPlayer();
			    $teller = new StoryTeller($story);

			    // create the supporting context for this story
			    $context = $player->createContext($staticConfig, $runtimeConfig, $envName, $story);
			    $teller->setStoryContext($context);
			    $teller->setRuntimeConfigManager($runtimeConfigManager);

			    // make the story happen
			    $result = $player->play($teller, $staticConfig);

			    // all done
			    break;

		    case "json":
		    	// we are running a list of stories

		    	// load the list of stories
		    	$storyList = StoryListLoader::loadList($params[0]);

		    	// keep track of the results
		    	$results = array();

		    	// run through our list of stories
		    	foreach ($storyList->stories as $storyFile)
		    	{
					// load our story
					$story = StoryLoader::loadStory($storyFile);

					// create something to play this story
				    $player = new StoryPlayer();
				    $teller = new StoryTeller($story);

				    // create the supporting context for this story
				    $context = $player->createContext($staticConfig, $runtimeConfig, $envName, $story);
				    $teller->setStoryContext($context);
				    $teller->setConfigLoader($configLoader);

				    // special case - reusable environments
				    if ($storyList->options->reuseTestEnvironment) {
				    	// we need to remember the staticConfig, as we are
				    	// probably about to override it
					    $origStaticConfig = clone $staticConfig;

				    	// story #1 - keep the test environment around
				    	if ($storyFile == $storyList->stories[0]) {
				    		// we do not override the user's preference for
				    		// the TestEnvironmentStartup

				    		// do not shutdown the TestEnvironment;
				    		// we want to re-use it in the other stories
				    		$staticConfig->phases->TestEnvironmentTeardown = false;
				    	}
				    	else if ($storyFile == end($storyList->stories)) {
				    		// do nothing - we do not want to override
				    		// the user's config file settings here
				    	}
				    	else {
				    		// we are running a story in the middle of the list
				    		//
				    		// do not re-create the test environment
				    		// do not destroy it afterwards
				    		$staticConfig->phases->TestEnvironmentSetup = false;
				    		$staticConfig->phases->testEnvironmentTeardown = false;
				    	}
				    }

				    // make the story happen
				    $results[] = $player->play($teller, $staticConfig);

				    // special case - reusable environments
				    if ($storyList->options->reuseTestEnvironment) {
					    // restore the original config
					    $staticConfig = clone $origStaticConfig;
				    }
		    	}

		    	// report on the final results
		    	$this->summariseStoryList($results);

		    	// all done
		    	break;

		    default:
		    	// unsupported!
		}

	    // write out any changed runtime config to disk
	    $runtimeConfigManager->saveRuntimeConfig($runtimeConfig);

	    // all done
	    return 0;
  	}

  	protected function summariseStoryList($storyResults)
	{
		// we need to make a pronouncement about the whole list of stories

		echo "\n";
		echo "============================================================\n";
		echo "FINAL RESULTS\n";
		echo "\n";

		foreach ($storyResults as $result)
		{
			echo $result->story->getName() . " :: " . StoryPlayer::$outcomeToText[$result->storyResult] . "\n";
		}
	}

}