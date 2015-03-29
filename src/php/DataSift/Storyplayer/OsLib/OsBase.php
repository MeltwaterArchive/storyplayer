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
 * @package   Storyplayer/OsLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\OsLib;

use stdClass;
use DataSift\Storyplayer\CommandLib\CommandClient;
use DataSift\Storyplayer\HostLib\SupportedHost;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Stone\ObjectLib\BaseObject;

/**
 * the things you can do / learn about a machine running one of our
 * supported operatating systems
 *
 * @category  Libraries
 * @package   Storyplayer/OsLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
abstract class OsBase implements SupportedOs
{
    /**
     *
     * @var DataSift\Storyplayer\PlayerLib\StoryTeller;
     */
    protected $st;

    public function __construct(StoryTeller $st)
    {
        // remember for future use
        $this->st = $st;
    }

    /**
     *
     * @param  HostDetails $hostDetails
     * @param  SupportedHost $host
     * @return string
     */
    abstract public function determineIpAddress($hostDetails, SupportedHost $host);

    /**
     *
     * @param  HostDetails   $hostDetails
     * @param  SupportedHost $vm
     * @return string
     */
    abstract public function determineHostname($hostDetails, SupportedHost $vm);

    /**
     * check a given hostname to make sure it is safe to use
     *
     * @param  HostDetails $hostDetails
     *         the known facts about the host
     * @param  string $hostname
     *         the hostname we want to check
     * @return string
     *         the hostname that can be added to the hostDetails data
     */
    protected function runHostnameSafeguards($hostDetails, $hostname)
    {
        // special case - hostname is already set in hostDetails
        //
        // we assume that it was set in the test environment config file
        // by someone who knows what they are doing
        if (isset($hostDetails->hostname)) {
            return $hostDetails->hostname;
        }

        // special case - no IP address for the host
        //
        // we need the host's IP address to perform our checks
        if (!isset($hostDetails->ipAddress)) {
            return $hostname;
        }

        // check for machines calling themselves 'localhost' in some form
        // or another
        //
        // this can happen when there is no working dynamic DNS
        $parts = explode(".", $hostname);
        if ($parts[0] == 'localhost') {
            // this is only valid *if* the host's IP address is recognised
            // as a valid loopback address
            //
            // most of the world uses 127.0.0.1
            // ubuntu uses 127.0.1.1 (no idea why)
            $validIps = [ '127.0.0.1', '127.0.1.1' ];
            if (!in_array($hostDetails->ipAddress, $validIps)) {
                // this hostname is *not* safe
                //
                // the best we can do is return the host's IP address
                return $hostDetails->ipAddress;
            }
        }

        // check if the machine's hostname resolves to the detected IP
        // address or not
        $resolvedIp = gethostbyname($hostname);
        if ($resolvedIp != $hostDetails->ipAddress) {
            // no good
            //
            // possibilities include:
            //
            // * no DNS entry for $hostname
            // * no /etc/hosts entry for $hostname
            //
            // the best we can do is return the host's IP address
            return $hostDetails->ipAddress;
        }

        // *if* we get here, then we believe that $hostname is safe to
        // use in your stories
        return $hostname;
    }

    /**
     *
     * @param  HostDetails $hostDetails
     * @param  string $packageName
     * @return BaseObject
     */
    abstract public function getInstalledPackageDetails($hostDetails, $packageName);

    /**
     *
     * @param  HostDetails $hostDetails
     * @param  string $processName
     * @return boolean
     */
    abstract public function getProcessIsRunning($hostDetails, $processName);

    /**
     *
     * @param  HostDetails $hostDetails
     * @param  string $processName
     * @return integer
     */
    abstract public function getPid($hostDetails, $processName);

    /**
     *
     * @param  \DataSift\Storyplayer\PlayerLib\Storyteller $st
     *         our module loader
     * @param  \DataSift\Storyplayer\HostLib\HostDetails $hostDetails
     *         the details for the host we want a client for
     * @return CommandClient
     */
    abstract public function getClient($st, $hostDetails);

    /**
     * @param HostDetails $hostDetails
     * @param string $command
     *
     * @return \DataSift\Storyplayer\CommandLib\CommandResult
     */
    public function runCommand($hostDetails, $command)
    {
        // get an SSH client
        $client = $this->getClient($this->st, $hostDetails);

        // run the command
        return $client->runCommand($command);
    }

    public function downloadFile($hostDetails, $sourceFilename, $destFilename)
    {
        // get a client
        $client = $this->getClient($this->st, $hostDetails);

        // attempt the upload
        return $client->downloadFile($sourceFilename, $destFilename);
    }

    public function uploadFile($hostDetails, $sourceFilename, $destFilename)
    {
        // get a client
        $client = $this->getClient($this->st, $hostDetails);

        // attempt the upload
        return $client->uploadFile($sourceFilename, $destFilename);
    }

    public function getFileDetails($hostDetails, $filename)
    {
        // get a client
        $client = $this->getClient($this->st, $hostDetails);

        // upload our Python script to help us out here
        $rand = rand(0, 999999);
        $destFilename = "/tmp/st-{$rand}.py";
        $client->uploadFile(__DIR__ . "/path_helper.py", $destFilename);

        // run the script to inspect the filename
        $statCommand = "python {$destFilename} {$filename}";
        $result = $client->runCommand($statCommand);

        // did it work?
        if ($result->returnCode !== 0) {
            return null;
        }
        $retval = json_decode($result->output);

        // all done
        return $retval;
    }
}