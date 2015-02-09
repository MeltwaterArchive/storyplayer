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
 * @package   Storyplayer/ConfigLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\ConfigLib;

use DataSift\Storyplayer\Injectables;
use DataSift\Storyplayer\SystemsUnderTestLib\SystemUnderTestConfig;
use DataSift\Storyplayer\TestEnvironmentsLib\TestEnvironmentConfig;

use Datasift\Os;
use Datasift\IfconfigParser;
use Datasift\netifaces;
use Datasift\netifaces\NetifacesException;

use DataSift\Stone\ObjectLib\BaseObject;

/**
 * the class for the config we make available to stories
 *
 * the ActiveConfig is a merging of:
 *
 * 1) the StoryplayerConfig
 * 2) the SystemUnderTestConfig of the chosen system-under-test (-s)
 * 3) the TestEnvironmentConfig of the chosen test-environment (-t)
 * 4) the DeviceConfig of the chosen test device (-d)
 * 5) the HostsTable from the RuntimeConfig
 * 6) the RolesTable from the RuntimeConfig
 *
 * @category  Libraries
 * @package   Storyplayer/ConfigLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class ActiveConfig extends WrappedConfig
{
	public function init(Injectables $injectables)
	{
		// we start off with the built-in config
		$this->mergeData('storyplayer', $injectables->defaultConfig);

		// these are the initial variables we want
		$this->setData('storyplayer.ipAddress', $this->getHostIpAddress());
		$this->setData('storyplayer.currentDir', getcwd());
		$this->setData('storyplayer.user.home', getenv('HOME'));

		// we also want to link in the hosts and roles tables, to make
		// it a lot easier for Prose modules
		$activeConfig         = $this->getConfig();
        $runtimeConfig        = $injectables->runtimeConfig;
        $runtimeConfigManager = $injectables->runtimeConfigManager;
        $testEnvName          = $injectables->activeTestEnvironmentName;

		$hostsTable = $runtimeConfigManager->getTable($runtimeConfig, 'hosts');
        if (!isset($hostsTable->$testEnvName)) {
            $hostsTable->$testEnvName = new BaseObject;
        }
        $activeConfig->hosts = $hostsTable->$testEnvName;

		$rolesTable = $runtimeConfigManager->getTable($runtimeConfig, 'roles');
        if (!isset($rolesTable->$testEnvName)) {
            $rolesTable->$testEnvName = new BaseObject;
        }
        $activeConfig->roles = $rolesTable->$testEnvName;
	}

	public function mergeStoryplayerConfig($injectables, $spConf)
	{
		$this->mergeData('storyplayer', $spConf);
	}

	public function mergeSystemUnderTestConfig($injectables, SystemUnderTestConfig $sutConfig = null)
	{
        // do we have a system under test?
        if (!isset($injectables->activeSystemUnderTestName)) {
            return;
        }

        // we want to remember the name of the system-under-test
        $this->setData('systemundertest.name', $injectables->activeSystemUnderTestName);

        // merge in the loaded config
        $this->mergeData('systemundertest', $sutConfig->getConfig());
	}

	public function mergeTestEnvironmentConfig($injectables, TestEnvironmentConfig $envConfig = null)
	{
        // do we have a test environment?
        if (!isset($injectables->activeTestEnvironmentName)) {
            $this->setData('target', null);
            return;
        }

        // we want to remember the name of the test environment
        $this->setData('target.name', $injectables->activeTestEnvironmentName);

        // merge in the loaded config
		$this->mergeData('target', $envConfig->getConfig());
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
        // our algorithm is simple:
        //
        // * we return the first non-loopback adapter that has an IP address
        // * if that fails, we return the first loopback adapter that has
        //   an IP address
        //
        // and if that fails, we give up

        try {
            // special case - when loopback is our only adapter
            $loopback = null;

            // loop over the adapters
            foreach ($adapters as $adapterToTest) {
                // does the adapter have an IP address?
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
                    //
                    // but wait - is it actually the loopback interface?
                    if (in_array($adapterToTest, ['lo0', 'lo']) && ($loopback == null)) {
                        $loopback = $ipAddress;
                    }
                    else {
                        return $ipAddress;
                    }
                }
            }

            // we didn't find any adapters with an IP address
            //
            // but is the loopback up and running?
            if ($loopback != null) {
                // this is better than throwing an error
                return $loopback;
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
