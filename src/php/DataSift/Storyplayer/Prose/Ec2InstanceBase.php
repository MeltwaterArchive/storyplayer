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

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;
use DataSift\Storyplayer\ProseLib\Prose;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

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
class Ec2InstanceBase extends Prose
{
	protected $vmDetails    = null;
	protected $instance     = null;
	protected $instanceName = '**unknown**';

	public function __construct(StoryTeller $st, $params = array())
	{
		// call our parent
		parent::__construct($st, $params);

		// get the VM details from the hosts table
		$this->vmDetails = $st->fromHostsTable()->getDetailsForHost($params[0]);

		if ($this->vmDetails) {
			// remember the name of this VM
			$this->instanceName = $this->vmDetails->ec2Name;

			// get the data about the instance from EC2
			$this->instance = $st->fromEc2()->getInstance($this->instanceName);

			// add the instance data to the vmDetails too, to keep that
			// up to date
			$this->vmDetails->ec2Instance = $this->instance;
		}
	}

	protected function requiresValidHost($method)
	{
		if (!$this->vmDetails) {
			throw new E5xx_ActionFailed($method, "No such host '{$this->args[0]}' in the hosts table");
		}

		// did we get anything?
		if (!$this->instance) {
			throw new E5xx_ActionFailed($method, "No such EC2 instance '{$this->instanceName}' at AWS");
		}
	}
}