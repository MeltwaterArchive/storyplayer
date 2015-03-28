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
class FromEc2 extends Prose
{
	public function getImage($amiId)
	{
		// what are we doing?
		$log = usingLog()->startAction("get data for EC2 image '{$amiId}'");

		// get the client
		$client = fromAws()->getEc2Client();

		// get the list of registered images
		$result = $client->describeImages();

		// is our image in there?
		foreach ($result['Images'] as $image) {
			if ($image['ImageId'] == $amiId) {
				// success!
				$log->endAction("image found");
				return $image;
			}
		}

		// if we get here, then we don't have the image
		$log->endAction("image does not exist");
		return null;
	}

	public function getInstance($instanceName)
	{
		// what are we doing?
		$log = usingLog()->startAction("get data for EC2 VM '{$instanceName}'");

		// get the client
		$client = fromAws()->getEc2Client();

		// get the list of running instances
		$result = $client->describeInstances();

		// loop through, and see if the instance is running
		foreach ($result['Reservations'] as $reservation) {
			foreach ($reservation['Instances'] as $instance) {
				$foundInstanceName = '';

				if (!isset($instance['Tags'])) {
					// no tags at all means no name to match against
					continue;
				}

				// skip terminated instances
				if ($instance['State']['Code'] == 48) {
					continue;
				}

				foreach ($instance['Tags'] as $tag) {
					if ($tag['Key'] == 'Name') {
						$foundInstanceName = $tag['Value'];
					}
				}

				if ($instanceName == $foundInstanceName) {
					// we're done here
					$instanceId = $instance['InstanceId'];
					$log->endAction("instance is running as ID '{$instanceId}'");
					return $instance;
				}
			}
		}

		// if we get here, the instance currently does not exist at EC2
		$log->endAction("instance does not exist");
		return null;
	}
}