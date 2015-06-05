<?php

/**
 * Copyright (c) 2011-present Mediasift Ltd
 * Copyright (c) 2015-present Ganbaro Digital Ltd
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
 * @package   Storyplayer/ConfigLib
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\ConfigLib;

use DataSift\Storyplayer\TestEnvironmentsLib\TestEnvironmentRuntimeConfig;

/**
 * tracks a list of test environments that are currently 'running'
 * i.e. the test environments we have runtime.json files for
 *
 * this replaces the 'hosts' and 'roles' section of the runtime.json file
 *
 * @category  Libraries
 * @package   Storyplayer/ConfigLib
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class RunningTestEnvironmentsList extends ConfigList
{
    public function __construct($searchFolder = 'storyplayer/test-environments')
    {
        parent::__construct(
        	'DataSift\Storyplayer\TestEnvironmentsLib\TestEnvironmentRuntimeConfig',
        	[
        		"storyplayer/test-environments"
        	],
            [
                new ConfigFinder("/runtime.json")
            ]
        );
    }

    public function getRuntimeConfigForTestEnvironment($testEnvName)
    {
        if (!$this->hasEntry($testEnvName)) {
            // we need to create a new entry
            $newConfig = new TestEnvironmentRuntimeConfig();
            $newConfig->setName($testEnvName);
            $newConfig->setFilename("storyplayer/test-environments/{$testEnvName}/runtime.json");
            $this->addEntry($testEnvName, $newConfig);
        }

        return $this->getEntry($testEnvName);
    }
}
