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

use DataSift\Stone\ObjectLib\BaseObject;

/**
 * get information about vagrant
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class VagrantDetermine extends HostDetermine
{
	public function __construct(StoryTeller $st, $args = array())
	{
		// call the parent constructor
		parent::__construct($st, $args);

		// arg[0] is the name of the box
		if (!isset($args[0])) {
			throw new E5xx_ActionFailed(__METHOD__, "Param #0 needs to be the name you've given to the machine");
		}

		// arg[1] should be the name of the operating system to use
		if (!isset($args[1])) {
			throw new E5xx_ActionFailed(__METHOD__, "Param #1 needs to be the name of the guest operating system");
		}

		// what is the name of the class that we need to find?
		$className = 'DataSift\Storyplayer\OsLib\\' . ucfirst(strtolower($args[1]));

		// do we have a class for this operating system?
		if (!class_exists($className)) {
			throw new E5xx_ActionFailed(__METHOD__, "Cannot find class '{$className}'");
		}

		// create our helper
		$this->guestOs = new $className($st);
	}

	public function getVmIsRunning()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("is VM '{$this->boxName}' running?");

		// get the VM details
		try {
			$boxDetails = $st->fromVagrant()->getDetails($this->boxName);
		}
		catch (E5xx_ActionFailed $e) {
			// the box does not exist
			return false;
		}

		// if the box is running, it should have a status of 'running'
		$status = '';
		$log->addStep("determine status of Vagrant VM", function() use($st, $this->boxName, &$status) {
			$command = "vagrant status | grep default | awk '{print \$2'}";
			$status = $st->usingVagrant()->runVagrantCommand($boxName, $command);
		});
		if ($status != 'running') {
			$log->endAction("VM is not running; state is '{$status}'");
			return false;
		}

		// all done
		$log->endAction("VM is running");
		return true;
	}
}