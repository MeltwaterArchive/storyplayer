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
use Phix_Project\CliEngine;
use Phix_Project\CliEngine\CliCommand;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\Injectables;

/**
 * Support for registering 'localhost' as a host you can interact
 * with
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Feature_LocalhostSupport implements Feature
{
    public function addSwitches(CliCommand $command, $additionalContext)
    {
        // nothing to do - localhost is always enabled
    }

    public function initBeforeModulesAvailable(CliEngine $engine, CliCommand $command, Injectables $injectables)
    {
        // create a definition for localhost
        $host = new BaseObject();
        $host->hostId      = "localhost";
        $host->osName      = $this->detectOs();
        $host->type        = "PhysicalHost";
        $host->ipAddress   = "127.0.0.1";
        $host->hostname    = "localhost";
        $host->provisioned = true;

        // we need to make sure it's registered in the hosts table
        $runtimeConfigManager = $injectables->runtimeConfigManager;
        $hostsTable = $runtimeConfigManager->getTable($injectables->runtimeConfig, 'hosts');
        $testEnv = $injectables->activeTestEnvironmentName;

        if (!isset($hostsTable->$testEnv)) {
            $hostsTable->$testEnv = new BaseObject();
        }
        $hostsTable->$testEnv->localhost = $host;
    }

    protected function detectOs()
    {
        if (stristr(PHP_OS, 'DAR')) {
            return "Localhost_OSX";
        }
        else if (stristr(PHP_OS, 'WIN')) {
            return "Localhost_Windows";
        }
        else if (stristr(PHP_OS, 'LINUX')) {
            // @TODO: detect the different types of Linux here
            return "Localhost_Unix";
        }
        else {
            return "Localhost_Unix";
        }
    }

    public function initAfterModulesAvailable(StoryTeller $st, CliEngine $engine, Injectables $injectables)
    {
        // no-op
    }
}
