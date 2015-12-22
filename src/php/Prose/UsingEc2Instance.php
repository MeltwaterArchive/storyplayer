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
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Prose;

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
class UsingEc2Instance extends Ec2InstanceBase
{
    public function createImage($imageName)
    {
        $this->requiresValidHost(__METHOD__);

        // what are we doing?
        $log = usingLog()->startAction("create EBS AMI image '{$imageName}' from EC2 VM '{$this->instanceName}'");

        // get the AWS EC2 client to work with
        $ec2Client = fromAws()->getEc2Client();

        $response = $ec2Client->createImage(array(
            "InstanceId" => $this->instance['InstanceId'],
            "Name" => $imageName
        ));

        // did we get an image ID back?
        if (!isset($response['ImageId'])) {
            throw Exceptions::newActionFailedException(__METHOD__, "no ImageId returned from EC2 :(");
        }

        // all done
        $log->endAction("created AMI image '{$response['ImageId']}'");
        return $response['ImageId'];
    }

    public function markAllVolumesAsDeleteOnTermination()
    {
        $this->requiresValidHost(__METHOD__);

        // what are we doing?
        $log = usingLog()->startAction("mark all volumes on EC2 VM '{$this->instanceName}' to be deleted on termination");

        // create a list of all of the volumes we're going to modify
        $ebsVolumes = array();
        foreach ($this->instance['BlockDeviceMappings'] as $origEbsVolume) {
            $ebsVolume = array(
                'DeviceName' => $origEbsVolume['DeviceName'],
                'Ebs' => array (
                    'DeleteOnTermination' => true
                )
            );

            $ebsVolumes[] = $ebsVolume;
        }

        // get the AWS EC2 client to work with
        $ec2Client = fromAws()->getEc2Client();

        // let's mark all of the volumes as needing to be deleted
        // on termination
        $ec2Client->modifyInstanceAttribute(array(
            'InstanceId' => $this->instance['InstanceId'],
            'BlockDeviceMappings' => $ebsVolumes
        ));

        // now, we need to make sure that actually worked
        $this->instance = fromEc2()->getInstance($this->instanceName);

        // var_dump("\n\n\nAFTER MODIFY INSTANCE ATTRIBUTE\n\n");
        // var_dump($this->instance);

        // that should be that
        $log->endAction();
    }
}