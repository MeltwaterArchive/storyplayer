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
 * @package   Storyplayer/Phases
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Phases;

use Exception;
use DataSift\Storyplayer\HostLib;
use Storyplayer\SPv2\Modules\Exceptions\ActionFailedException;
use Storyplayer\SPv2\Modules\Exceptions\ExpectFailedException;
use Storyplayer\SPv2\Modules\Exceptions\NotImplementedException;

/**
 * the TestEnvironmentDestruction phase
 *
 * @category  Libraries
 * @package   Storyplayer/Phases
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

class TestEnvironmentDestructionPhase extends InfrastructurePhase
{
    public function doPhase($thingBeingPlayed = null)
    {
        // shorthand
        $st    = $this->st;

        // our return value
        $phaseResult = $this->getNewPhaseResult();

        // find out what we need to be doing
        $testEnvironmentConfig = $st->getTestEnvironmentConfig();

        // are there any machines to destroy?
        if (empty($testEnvironmentConfig)) {
            // nothing to do
            $phaseResult->setContinuePlaying();
            return $phaseResult;
        }

        // destroy the environments
        try {
            foreach ($testEnvironmentConfig->groups as $env) {
                // destroy the machine(s) in this environment, including:
                //
                // * destroying any virtual machines
                // * de-registering in the Hosts table
                // * de-registering in the Roles table
                $hostAdapter = HostLib::getHostAdapter($st, $env->type);
                $hostAdapter->destroyHost($env);
            }

            // all the hosts from the config are gone
            //
            // now, let's get rid of any hosts (e.g. localhost) that
            // Storyplayer has injected into the table elsewhere
            usingHostsTable()->emptyTable();
            usingRolesTable()->emptyTable();

            // remove the test environment signature
            usingTargetsTable()->removeCurrentTestEnvironment();

            // all done
            $phaseResult->setContinuePlaying();
        }
        catch (ActionFailedException $e) {
            $phaseResult->setPlayingFailed(
                $phaseResult::FAILED,
                $e->getMessage(),
                $e
            );
        }
        catch (ExpectFailedException $e) {
            $phaseResult->setPlayingFailed(
                $phaseResult::FAILED,
                $e->getMessage(),
                $e
            );
        }
        // if anything is marked as incomplete, deal with that too
        catch (NotImplementedException $e) {
            $phaseResult->setPlayingFailed(
                $phaseResult::INCOMPLETE,
                $e->getMessage(),
                $e
            );
        }
        catch (Exception $e) {
            $phaseResult->setPlayingFailed(
                $phaseResult::ERROR,
                $e->getMessage(),
                $e
            );
        }

        // all done
        return $phaseResult;
    }
}
