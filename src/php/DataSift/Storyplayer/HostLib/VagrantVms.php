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
 * @package   Storyplayer/HostLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\HostLib;

use DataSift\Storyplayer\CommandLib\CommandRunner;
use DataSift\Storyplayer\CommandLib\CommandResult;
use DataSift\Storyplayer\OsLib;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Stone\ObjectLib\BaseObject;
use Exception;
use Storyplayer\SPv2\Modules\Exceptions;
use Storyplayer\SPv2\Modules\Shell;

/**
 * the things you can do / learn about a group of Vagrant virtual machines
 *
 * @category  Libraries
 * @package   Storyplayer/HostLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class VagrantVms implements SupportedHost
{
    /**
     *
     * @var StoryTeller
     */
    protected $st;

    /**
     *
     * @param StoryTeller $st
     */
    public function __construct(StoryTeller $st)
    {
        // remember
        $this->st = $st;
    }

    /**
     * Check environmental details
     *
     * @param  stdClass $groupDef
     */
    protected function checkGroupDefinition($groupDef)
    {
        // make sure we like the provided details
        if (!isset($groupDef->details)) {
            throw Exceptions::newActionFailedException(__METHOD__, "missing groupDef->details");
        }
        if (!isset($groupDef->details->machines)) {
            throw Exceptions::newActionFailedException(__METHOD__, "missing groupDef->details->machines");
        }
        if (empty($groupDef->details->machines)) {
            throw Exceptions::newActionFailedException(__METHOD__, "groupDef->details->machines cannot be empty");
        }

        // make sure we have a Vagrantfile
        $expectedVagrantfile = $this->getVagrantDir($groupDef) . "/Vagrantfile";
        if (!file_exists($expectedVagrantfile)) {
            throw Exceptions::newActionFailedException(__METHOD__, "no Vagrantfile; expected it to be here: {$expectedVagrantfile}");
        }

    }

    /**
     * Get the Vagrant directory
     *
     * @param  stdClass $groupDef
     *
     * @return string
     */
    protected function getVagrantDir($groupDef)
    {
        if (isset($groupDef->baseFolder)) {
            return $groupDef->baseFolder;
        }

        return getcwd();
    }

    /**
     *
     * @param  stdClass $groupDef
     * @param  array $provisioningVars
     * @return void
     */
    public function createHost($groupDef, $provisioningVars = array())
    {
        // what are we doing?
        $log = usingLog()->startAction('create new VM');

        // make sure we're happy with this group
        $this->checkGroupDefinition($groupDef);

        // where is the action?
        $baseFolder = $this->getVagrantDir($groupDef);

        // make sure we're happy with details about the machine
        foreach($groupDef->details->machines as $hostId => $machine) {
            // TODO: it would be great to autodetect this one day
            if (!isset($machine->osName)) {
                throw Exceptions::newActionFailedException(__METHOD__, "missing groupDef->details->machines['$hostId']->osName");
            }
            if (!isset($machine->roles)) {
                throw Exceptions::newActionFailedException(__METHOD__, "missing groupDef->details->machines['$hostId']->roles");
            }
        }

        // make sure the VM is stopped, if it is running
        $log->addStep('stop vagrant VM in '.$baseFolder.' if already running', function() use($baseFolder) {
            $command = "vagrant destroy --force";
            $this->runCommandAgainstHostManager($baseFolder, $command);
        });

        // remove any existing hosts table entry
        foreach ($groupDef->details->machines as $hostId => $machine) {
            // remove any roles
            usingRolesTable()->removeHostFromAllRoles($hostId);

            // now drop the host
            usingHostsTable()->removeHost($hostId);
        }

        // work out which network interface to use
        $this->setVagrantBridgedInterface();

        // let's start the VM
        $command = "vagrant up";
        $result = $log->addStep('create vagrant VM(s) in '.$baseFolder, function() use($baseFolder, $command) {
            return $this->runCommandAgainstHostManager($baseFolder, $command);
        });

        // did it work?
        if ($result->returnCode != 0) {
            $log->endAction("VM failed to start or provision :(");
            throw Exceptions::newActionFailedException(__METHOD__);
        }

        // yes it did!!

        // store the details
        foreach($groupDef->details->machines as $hostId => $machine)
        {
            // we want all the details from the config file
            $vmDetails = clone $machine;

            // this allows the story to perform actions against a single
            // machine if required
            $vmDetails->type        = 'VagrantVm';

            // new in v2.x:
            //
            // when provisioning a folder of vagrant vms, we now use
            // the same name for the VM that vagrant uses
            $vmDetails->hostId      = $hostId;

            // remember where the machine lives
            $vmDetails->dir         = $baseFolder;

            // we need to remember how to SSH into the box
            $vmDetails->sshUsername = 'vagrant';
            $vmDetails->sshKeyFile  = $this->determinePrivateKey($vmDetails);
            $vmDetails->sshOptions  = [
                "-i '" . $vmDetails->sshKeyFile . "'",
                "-o StrictHostKeyChecking=no",
                "-o UserKnownHostsFile=/dev/null",
                "-o LogLevel=quiet",
            ];
            $vmDetails->scpOptions = [
                "-i '" . $vmDetails->sshKeyFile . "'",
                "-o StrictHostKeyChecking=no",
            ];

            // remember how to connect to the machine via the network
            $vmDetails->ipAddress   = $this->determineIpAddress($vmDetails);
            $vmDetails->hostname    = $this->determineHostname($vmDetails);

            // mark the box as provisioned
            // we will use this in stopBox() to avoid destroying VMs that failed
            // to provision
            $vmDetails->provisioned = true;

            // remember this vm, now that it is running
            usingHostsTable()->addHost($vmDetails->hostId, $vmDetails);
            foreach ($vmDetails->roles as $role) {
                usingRolesTable()->addHostToRole($vmDetails, $role);
            }

            // now, let's get this VM into our SSH known_hosts file, to avoid
            // prompting people when we try and provision this VM
            $log->addStep("get the VM into the SSH known_hosts file", function() use($vmDetails) {
                Shell::onHost($vmDetails->hostId)->runCommand("ls");
            });
        }

        // all done
        $log->endAction();
    }

    /**
     *
     * @param  stdClass $envDetails
     * @return void
     */
    public function startHost($envDetails)
    {
        // if you really want to do this from your story, use
        // $st->usingVagrantVm()->startHost()
        throw Exceptions::newActionFailedException(__METHOD__, "unsupported operation");
    }

    /**
     *
     * @param  stdClass $envDetails
     * @return void
     */
    public function stopHost($envDetails)
    {
        // if you really want to do this from your story, use
        // $st->usingVagrantVm()->stopHost()
        throw Exceptions::newActionFailedException(__METHOD__, "unsupported operation");
    }

    /**
     *
     * @param  stdClass $envDetails
     * @return void
     */
    public function restartHost($envDetails)
    {
        // if you really want to do this from your story, use
        // $st->usingVagrantVm()->restartHost()
        throw Exceptions::newActionFailedException(__METHOD__, "unsupported operation");
    }

    /**
     *
     * @param  stdClass $envDetails
     * @return void
     */
    public function powerOffHost($envDetails)
    {
        // if you really want to do this from your story, use
        // $st->usingVagrantVm()->powerOffHost()
        throw Exceptions::newActionFailedException(__METHOD__, "unsupported operation");
    }

    /**
     *
     * @param  stdClass $groupDef
     * @return void
     */
    public function destroyHost($groupDef)
    {
        // what are we doing?
        $log = usingLog()->startAction("destroy VM(s)");

        // stop all the VMs, one by one
        foreach ($groupDef->details->machines as $hostId => $machine) {
            // get the machine details
            $vmDetails = fromHostsTable()->getDetailsForHost($hostId);
            if ($vmDetails) {
                // is the VM actually running?
                if (fromHost($hostId)->getHostIsRunning()) {
                    // delete the VM from disk
                    //
                    // this will also deregister the host from the
                    // HostsTable and RolesTable
                    usingVagrant()->destroyVm($hostId);
                }
            }
        }

        // all done
        $log->endAction();
    }

    /**
     *
     * @param  string $baseFolder
     * @param  string $command
     * @return CommandResult
     */
    public function runCommandAgainstHostManager($baseFolder, $command)
    {
        // what are we doing?
        $log = usingLog()->startAction("run vagrant command '{$command}'");

        // build the command
        $fullCommand = "cd '{$baseFolder}' && $command 2>&1";

        // run the command
        $commandRunner = new CommandRunner();
        $result = $commandRunner->runSilently($fullCommand);

        // all done
        $log->endAction("return code was '{$result->returnCode}'");
        return $result;
    }

    /**
     * @param  string $baseFolder
     * @param  string $command
     * @return CommandResult
     */
    public function runCommandViaHostManager($baseFolder, $command)
    {
        // what are we doing?
        $log = usingLog()->startAction("run vagrant command '{$command}'");

        // build the command
        $fullCommand = "cd '{$baseFolder}' && vagrant ssh -c \"$command\"";

        // run the command
        $commandRunner = new CommandRunner();
        $result = $commandRunner->runSilently($fullCommand);

        // all done
        $log->endAction("return code was '{$result->returnCode}'");
        return $result;
    }

    /**
     *
     * @param  stdClass $envDetails
     * @return boolean
     */
    public function isRunning($envDetails)
    {
        throw Exceptions::newActionFailedException(__METHOD__, "unsupported operation");
    }

    /**
     *
     * @param  stdClass $vmDetails
     * @return string
     */
    public function determineIpAddress($vmDetails)
    {
        // what are we doing?
        $log = usingLog()->startAction("determine IP address of Vagrant VM '{$vmDetails->hostId}'");

        // create an adapter to talk to the host operating system
        $host = OsLib::getHostAdapter($this->st, $vmDetails->osName);

        // get the IP address
        $ipAddress = $host->determineIpAddress($vmDetails, new VagrantVm($this->st));

        // all done
        $log->endAction("IP address is '{$ipAddress}'");
        return $ipAddress;
    }

    /**
     *
     * @param  stdClass $vmDetails
     * @return string
     */
    public function determineHostname($vmDetails)
    {
        // what are we doing?
        $log = usingLog()->startAction("determine hostname of Vagrant VM '{$vmDetails->hostId}'");

        // create an adapter to talk to the host operating system
        $host = OsLib::getHostAdapter($this->st, $vmDetails->osName);

        // get the hostname
        $hostname = $host->determineHostname($vmDetails, new VagrantVm($this->st));

        // all done
        $log->endAction("hostname is '{$hostname}'");
        return $hostname;
    }

    /**
     * Set the VAGRANT_BRIDGE_ADAPTER and VIRTUALBOX_BRIDGE_ADAPTER
     * environmental variables.
     */
    public function setVagrantBridgedInterface() {
        $bridgedIface = $this->determineBridgedInterface();
        putenv('VAGRANT_BRIDGE_ADAPTER='.$bridgedIface);
        putenv('VIRTUALBOX_BRIDGE_ADAPTER='.$bridgedIface);
    }

    /**
     * @return string
     */
    public function determineBridgedInterface()
    {
        // what are we doing?
        $log = usingLog()->startAction("determine bridged network interface for Vagrant VM");

        try {
            // 1. try to load Vagrant settings from storyplayer.json
            // e.g.: "moduleSettings":{"vagrant":{"bridgedIface":"eth0"}}
            $vagrantSettings = fromStoryplayer()->getModuleSetting('vagrant');
            if (!empty($vagrantSettings->bridgedIface)) {
                $log->endAction('Returning configured '.$vagrantSettings->bridgedIface.' interface');
                return $vagrantSettings->bridgedIface;
            }
        } catch (Exception $e) {
            // ignore errors as this setting may not exist
        }

        // 2. check if VirtualBox (VBoxManage) is installed
        $command = 'which VBoxManage';
        $commandRunner = new CommandRunner();
        $result = $commandRunner->runSilently($command);
        if ($result->returnCode !== 0) {
            // VBoxManage is not installed, we are probably using another provider
            // like OpenStack that do not require this setting
            $log->endAction('VBoxManage is not installed: returning default eth0 interface');
            return 'eth0';
        }

        // 3. VBoxManage can actually tell us what we need to know
        $command = 'VBoxManage list bridgedifs';
        $commandRunner = new CommandRunner();
        $result = $commandRunner->runSilently($command);
        if ($result->returnCode != 0) {
            $log->endAction('unable to get list of bridgable network interfaces from VBoxManage :(');
            throw Exceptions::newActionFailedException(__METHOD__);
        }

        // now we just need to make sense of it all
        $lines = explode("\n", $result->output);
        $iface = null;
        foreach($lines as $line) {
            $matches = [];
            if (preg_match("|Name:[\s]+(.*)|", $line, $matches)) {
                $iface = $matches[1];
            }
            else if ($iface !== null && preg_match("|IPAddress:[\s]+(.*)|", $line, $matches)) {
                // our network interface contains an IPAddress - it is likely
                // to be one that works
                if ($matches[1] != '0.0.0.0') {
                    $log->endAction($iface);
                    return $iface;
                }
            }
        }

        // if we get here, then we haven't found a network interface to use
        $log->endAction("no bridgeable network interface found :(");
        throw Exceptions::newActionFailedException(__METHOD__);
    }

    public function determinePrivateKey($vmDetails)
    {
        // what are we doing?
        $log = usingLog()->startAction("determine private key for Vagrant VM '{$vmDetails->hostId}'");

        // the key will be in one of two places, in this order:
        //
        // <test environment folder>/.vagrant/machines/:name/virtualbox/private_key
        // $HOME/.vagrant.d/insecure_private_key
        //
        // we use the first that we can find
        $keyFilenames = [
            $vmDetails->dir . "/.vagrant/machines/{$vmDetails->hostId}/virtualbox/private_key",
            getenv("HOME") . "/.vagrant.d/insecure_private_key"
        ];

        foreach ($keyFilenames as $keyFilename)
        {
            usingLog()->writeToLog("checking if {$keyFilename} exists");
            if (file_exists($keyFilename)) {
                $log->endAction($keyFilename);
                return $keyFilename;
            }
        }

        // if we get here, then we do not know where the private key is
        $log->endAction("unable to find Vagrant private key for VM");
        throw Exceptions::newActionFailedException(__METHOD__);
    }
}
