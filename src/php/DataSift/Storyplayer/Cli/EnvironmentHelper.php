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

use stdClass;

use Phix_Project\CliEngine;
use Phix_Project\CliEngine\CliCommand;
use Phix_Project\CliEngine\CliEngineSwitch;
use Phix_Project\CliEngine\CliResult;

use DataSift\Stone\ConfigLib\E5xx_ConfigFileNotFound;
use DataSift\Stone\ConfigLib\E5xx_InvalidConfigFile;
use DataSift\Stone\LogLib\Log;

use DataSift\Storyplayer\PlayerLib\StoryContext;
use DataSift\Storyplayer\PlayerLib\StoryPlayer;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\StoryLib\StoryLoader;
use DataSift\Storyplayer\StoryListLib\StoryListLoader;
use DataSift\Storyplayer\UserLib\User;
use DataSift\Storyplayer\UserLib\GenericUserGenerator;
use DataSift\Storyplayer\UserLib\ConfigUserLoader;
use DataSift\Storyplayer\Prose\E5xx_NoMatchingActions;

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
class EnvironmentHelper
{
    static public function getDefaultEnvironmentName($envList)
    {
        // what is the name of the environment for the computer that
        // we are running on?
        $defaultEnvName = self::getLocalEnvironmentName();

        // is there more than one test environment?
        if (count($envList) == 1) {
            $defaultEnvName = $envList[0];
        }

        // all done
        return $defaultEnvName;
    }

    static public function getLocalEnvironmentName()
    {
        // for convenience, the current computer's hostname will be the
        // default environment
        $defaultEnvName = getHostname();

        // we get different results on different operating systems
        // make sure the hostname is not the FQDN
        $dotPos = strpos($defaultEnvName, '.');
        if ($dotPos) {
            $defaultEnvName = substr($defaultEnvName, 0, $dotPos);
        }

        // all done
        return $defaultEnvName;
    }
}
