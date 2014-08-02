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

use DataSift\Stone\ConfigLib\E5xx_ConfigFileNotFound;
use DataSift\Stone\ConfigLib\E5xx_InvalidConfigFile;
use DataSift\Stone\ObjectLib\BaseObject;

use Datasift\Os;
use Datasift\IfconfigParser;
use Datasift\netifaces;
use Datasift\netifaces\NetifacesException;

/**
 * support for working with Storyplayer's config file
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
trait Injectables_ActiveConfigSupport
{
	public $activeConfig;

	public function initActiveConfigSupport(Injectables $injectables)
	{
		// shorthand
		$output = $injectables->output;

		// start with what we know about where storyplayer is running
		//
		// this gives us an initial set of variables that can be supported
		// in the storyplayer.json config file
		$this->activeConfig = new BaseObject;
		$this->activeConfig->storyplayer = new BaseObject;
        $this->activeConfig->storyplayer->ipAddress  = $this->getHostIpAddress();
        $this->activeConfig->storyplayer->currentDir = getcwd();
        $this->activeConfig->storyplayer->user = new BaseObject;
        $this->activeConfig->storyplayer->user->home = getenv('HOME');

		// add in what we know from the default config
		$this->activeConfig->storyplayer->mergeFrom($injectables->defaultConfig);

		// add in the storyplayer config file
		$config = json_decode($injectables->templateEngine->render(
			$injectables->storyplayerConfig,
			(array)$this->activeConfig
		));
		$this->activeConfig->storyplayer->mergeFrom($config);

        // we need to link the hostsTable and rolesTable for this
        // test environment into the activeConfig
        $runtimeConfig        = $injectables->runtimeConfig;
        $runtimeConfigManager = $injectables->runtimeConfigManager;
        $testEnvName          = $injectables->activeTestEnvironmentName;

		$hostsTable = $runtimeConfigManager->getTable($runtimeConfig, 'hosts');
        if (!isset($hostsTable->$testEnvName)) {
            $hostsTable->$testEnvName = new BaseObject;
        }
        $this->activeConfig->hosts = $hostsTable->$testEnvName;

		$rolesTable = $runtimeConfigManager->getTable($runtimeConfig, 'roles');
        if (!isset($rolesTable->$testEnvName)) {
            $rolesTable->$testEnvName = new BaseObject;
        }
        $this->activeConfig->roles = $rolesTable->$testEnvName;

		// all done
		return $this->activeConfig;
	}

    protected function getHostIpAddress()
    {
        // step 1 - how many adapters do we have on this box?
        // @todo Maybe we want to move this somewhere more central later?
        $os = Os::getOs();
        $parser = IfconfigParser::fromDistributions($os->getPossibleClassNames());
        $netifaces = new netifaces($os, $parser);

        $adapters = $netifaces->listAdapters();
        if (empty($adapters)) {
            throw new Exception("unable to parse host machine network adapters list");
        }

        // step 2 - find an adapter that is most likely to have the IP address
        // that we want
        //
        // note: am not sure that the search list for OSX interfaces is
        // reliable :(

        try {
            $searchList = array("br0", "p2p1", "eth0", "en2", "en0", "en1", "wlan0");
            foreach ($searchList as $adapterToTest) {
                // skip over any adapters that don't exist on this machine
                if (!in_array($adapterToTest, $adapters)) {
                    continue;
                }

                // we think the adapter is present
                //
                // does it have an IP address?
                try {
                    $ipAddress = $netifaces->getIpAddress($adapterToTest);
                } catch(NetifacesException $e){
                    // We couldn't get an IP address
                    $ipAddress = null;
                }

                // did we get back a valid IP address?
                $parts = explode('.', $ipAddress);
                if (count($parts) == 4) {
                    // success!
                    return $ipAddress;
                }
            }

            // if we get here, we could not determine the IP address of our
            // host :(
            //
            // this sucks
            throw new NetifacesException("Unable to determine IP address");

        } catch (NetifacesException $e){
            throw new Exception("could not determine IP address of host machine");
        }
    }
}
