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

use DataSift\Stone\ObjectLib\BaseObject;

/**
 * support for the system-under-test that the user chooses
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
trait Injectables_ActiveSystemUnderTestConfigSupport
{
	public $activeSystemUnderTestName;

	public function initActiveSystemUnderTestConfigSupport($sutName, $injectables)
	{
        // does the system-under-test exist?
        if (!isset($injectables->knownSystemsUnderTest->$sutName)) {
            throw new E4xx_NoSuchSystemUnderTest($sutName);
        }

        // a helper to load the config
        $staticConfigManager = $injectables->staticConfigManager;

        // load the config file for the system-under-test
        $activeSut = $staticConfigManager->loadConfigFile(
            $injectables->knownSystemsUnderTest->$sutName
        );

        // we need to merge the config for this system-under-test into
        // our active test environment config
        //
        // we're going to add the params section from our system-under-test
        // to each host in the active test environment that can host
        // the system-under-test
        //
        // we use the 'roles' data to match the two up

        $activeTestEnv = new BaseObject;
        $activeTestEnv->mergeFrom(json_decode($injectables->activeTestEnvironmentConfig));

        foreach ($activeSut as $sutDetails) {
            foreach ($activeTestEnv as $envDetails) {
                foreach ($envDetails->details->machines as $machine) {
                    if (in_array($sutDetails->role, $machine->roles) || in_array('*', $machine->roles)) {
                        if (!isset($machine->params)) {
                            $machine->params = new BaseObject;
                        }
                        $machine->params->mergeFrom($sutDetails->params);
                    }
                }
            }
        }

        // we need to store the test environment's config as a string,
        // as it will need expanding as we provision the test environment
        $injectables->activeTestEnvironmentConfig = json_encode($activeTestEnv);

        // remember the system-under-test
        $this->activeSystemUnderTestName = $sutName;
	}
}
