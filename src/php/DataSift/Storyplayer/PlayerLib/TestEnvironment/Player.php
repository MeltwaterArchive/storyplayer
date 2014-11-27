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

use DataSift\Storyplayer\Injectables;

/**
 * constructs / demolishes test environments around stories / tales
 *
 * @category  Libraries
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class TestEnvironment_Player
{
    protected $startupPhases;
    protected $shutdownPhases;
    protected $wrappedPlayers;

    public function __construct($wrappedPlayers, Injectables $injectables)
    {
        $this->wrappedPlayers = $wrappedPlayers;

        $this->startupPhases  = $injectables->activeConfig->storyplayer->phases->testEnvStartup;
        $this->shutdownPhases = $injectables->activeConfig->storyplayer->phases->testEnvShutdown;
    }

    public function play(StoryTeller $st, Injectables $injectables)
    {
        // shorthand
        $output = $st->getOutput();

        // we're building / destroying a test environment
        $activity = 'Creating test environment';
        $testEnv = new TestEnvironment($injectables->activeTestEnvironmentName);

        // we're using this to build / destroy our test environment
        $phasesPlayer = new PhaseGroup_Player();

        // announce what we're doing
        $output->startPhaseGroup($activity, $injectables->activeTestEnvironmentName);

        // run the startup phase
        $phasesPlayer->playPhases(
            $activity,
            $st,
            $injectables,
            $this->startupPhases,
            $testEnv
        );
        $creationResult = $testEnv->getResult();
        $output->endPhaseGroup($creationResult);

        // what happened?
        if (!$creationResult->getPhaseGroupSucceeded() && !$creationResult->getPhaseGroupSkipped()) {
            $output->logCliError("failed to create test environment - cannot continue");
            exit(1);
        }

        // now we need to play whatever runs against this
        // test environment
        foreach ($this->wrappedPlayers as $wrappedPlayer)
        {
            // play the wrapped item
            //
            // this is normally a story
            $wrappedPlayer->play($st, $injectables);

            // make sure the test device has stopped after each story
            $st->stopDevice();
        }

        // announce what we're doing
        $activity = 'Destroying test environment';
        $output->startPhaseGroup($activity, $injectables->activeTestEnvironmentName);

        // run the shutdown phase
        $testEnv->resetResult();
        $phasesPlayer->playPhases(
            $activity,
            $st,
            $injectables,
            $this->shutdownPhases,
            $testEnv
        );
        $output->endPhaseGroup($testEnv->getResult());

        // all done
    }
}
