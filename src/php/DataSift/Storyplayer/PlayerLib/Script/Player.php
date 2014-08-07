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
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\PlayerLib;

use DataSift\Storyplayer\Cli\Injectables;

/**
 * the main class for animating a single story
 *
 * @category  Libraries
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Script_Player
{
	/**
	 * path to the script that we are going to run
	 *
	 * @var string
	 */
	protected $scriptFilename;

	/**
	 * a list of the phases we need to run to get everything ready to
	 * run the script
	 *
	 * @var array
	 */
	protected $startupPhases;

	/**
	 * a list of the phases that make up the script
	 *
	 * @var array
	 */
	protected $scriptPhases;

	/**
	 * a list of the phases that we need to run once the script has
	 * finished
	 *
	 * @var array
	 */
	protected $shutdownPhases;

	public function __construct($scriptFilename, Injectables $injectables)
	{
		$this->scriptFilename = $scriptFilename;
		$this->startupPhases  = [];
		$this->scriptPhases   = $injectables->activeConfig->storyplayer->phases->script;
		$this->shutdownPhases = [];
	}

	public function play(StoryTeller $st, Injectables $injectables)
	{
		// shorthand
		$output = $st->getOutput();

        // we're going to use this to play our setup and teardown phases
        $phasesPlayer = new PhaseGroup_Player();

        // tell $st what we are going to run
        $st->setScriptFilename($this->scriptFilename);

        // run the startup phase
        $phasesPlayer->playPhases(
        	$st,
        	$injectables,
        	$this->startupPhases
        );

		// run the phases in the 'story' section
		$phaseResults = $phasesPlayer->playPhases(
			$st,
			$injectables,
			$this->scriptPhases
		);

		// play the 'paired' phases too, in case they haven't yet
		// executed correctly
		$phasesPlayer->playPairedPhases(
			$st,
			$injectables,
			$this->scriptPhases,
			$phaseResults
		);

		// run the shutdown phase
        $phasesPlayer->playPhases(
			$st,
			$injectables,
			$this->shutdownPhases
        );

		// all done
	}
}
