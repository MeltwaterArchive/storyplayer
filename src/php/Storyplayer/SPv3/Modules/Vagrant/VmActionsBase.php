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
 * @package   Storyplayer/Modules/Vagrant
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv3\Modules\Vagrant;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\HostLib;
use Storyplayer\SPv3\Modules\Host;
use Storyplayer\SPv3\Modules\Log;

/**
 * base class & API for different types of virtual hosting
 *
 * @category  Libraries
 * @package   Storyplayer/Modules/Vagrant
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class VmActionsBase extends Prose
{
    public function __construct(StoryTeller $st, $args = array())
    {
        // call the parent constructor
        parent::__construct($st, $args);
    }

    public function destroyVm($vmName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("destroy VM '{$vmName}'");

        // get the VM details
        $vmDetails = Host::fromHost($vmName)->getDetails();

        // create our host adapter
        $host = HostLib::getHostAdapter($this->st, $vmDetails->type);

        // stop the VM
        $host->destroyHost($vmDetails);

        // all done
        $log->endAction();
    }

    public function stopVm($vmName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("stop VM '{$vmName}'");

        // get the VM details
        $vmDetails = Host::fromHost($vmName)->getDetails();

        // create our host adapter
        $host = HostLib::getHostAdapter($this->st, $vmDetails->type);

        // stop the VM
        $host->stopHost($vmDetails);

        // all done
        $log->endAction();
    }

    public function powerOffVm($vmName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("power off VM '{$vmName}'");

        // get the VM details
        $vmDetails = Host::fromHost($vmName)->getDetails();

        // create our host adapter
        $host = HostLib::getHostAdapter($this->st, $vmDetails->type);

        // stop the VM
        $host->stopHost($vmDetails);

        // all done
        $log->endAction();
    }

    public function restartVm($vmName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("restart VM '{$vmName}'");

        // get the VM details
        $vmDetails = Host::fromHost($vmName)->getDetails();

        // create our host adapter
        $host = HostLib::getHostAdapter($this->st, $vmDetails->type);

        // restart our virtual machine
        $host->restartHost($vmDetails);

        // all done
        $log->endAction();
    }

    public function startVm($vmName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("start VM '{$vmName}'");

        // get the VM details
        $vmDetails = Host::fromHost($vmName)->getDetails();

        // create our host adapter
        $host = HostLib::getHostAdapter($this->st, $vmDetails->type);

        // restart our virtual machine
        $host->startHost($vmDetails);

        // all done
        $log->endAction();
    }
}
