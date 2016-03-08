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
 * @package   Storyplayer/Modules/Aws
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv2\Modules\Aws;

use Storyplayer\SPv2\Modules\Log;

/**
 * wrappers around the official Amazon EC2 SDK
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class FromEc2Instance extends Ec2InstanceBase
{
    public function getInstanceIsRunning()
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("determine if EC2 VM '{$this->instanceName}' is running");

        // does the instance exist?
        if (!$this->instance) {
            $log->endAction("no: instance does not exist");
            return false;
        }

        // is the instance running?
        if ($this->instance['State']['Code'] == 16) {
            // yes, it is
            $log->endAction("yes");
            return true;
        }
        else {
            // no, it is not
            $log->endAction("no: state is '{$this->instance['State']['Name']}'");
            return false;
        }
    }

    public function getInstanceVolumes()
    {
        // make sure we have a host to work with
        $this->requiresValidHost(__METHOD__);

        // what are we doing?
        $log = Log::usingLog()->startAction("retrieve configuration for all volumes attached to EC2 VM '{$this->instanceName}'");

        // does this instance have any block devices?
        if (isset($this->instance['BlockDeviceMappings'])) {
            $log->endAction("found " . count($this->instance['BlockDeviceMappings']) . " volumes");
            return $this->instance['BlockDeviceMappings'];
        }

        // if we get here, the instance has no volumes, which is
        // a little weird
        $log->endAction("no volumes found");
        return array();
    }

    public function getPublicDnsName()
    {
        // make sure we have a host to work with
        $this->requiresValidHost(__METHOD__);

        // what are we doing?
        $log = Log::usingLog()->startAction("get the public DNS name for EC2 VM '{$this->instanceName}'");

        // here are the details, as a string
        $dnsName = $this->instance['PublicDnsName'];

        // does it *have* a public DNS name?
        if (empty($dnsName)) {
            $log->endAction("EC2 VM does not have a public DNS name; has it finished booting?");
            return null;
        }

        // all done
        $log->endAction("public DNS name is: '{$dnsName}'");
        return $dnsName;
    }
}
