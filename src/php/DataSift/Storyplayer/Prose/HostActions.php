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

use DataSift\Storyplayer\HostLib;
use DataSift\Storyplayer\OsLib;
use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;
use DataSift\Storyplayer\ProseLib\HostBase;
use DataSift\Storyplayer\ProseLib\Prose;
use DataSift\Storyplayer\PlayerLib\StoryPlayer;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

use DataSift\Stone\ObjectLib\BaseObject;

/**
 * do things with vagrant
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class HostActions extends HostBase
{
	public function runCommand($command, $params)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("run command '{$command}' on host '{$this->hostDetails->name}'");

		// make sure we have valid host details
		$this->requireValidHostDetails(__METHOD__);

		// get an object to talk to this host
		$host = OsLib::getHostAdapter($st, $this->hostDetails->osName);

		// run the command in the guest operating system
		$result = $host->runCommand($this->hostDetails, $command, $params);

		// all done
		$log->endAction();
		return $result;
	}

	public function writePlaybookVars($pathToVmHomeFolder, $playbookVars)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("write out ansible playbook vars");

		// where are we writing to?
		$parts = explode('-', $pathToVmHomeFolder);
		if (count($parts) < 2) {
			$log->endAction("cannot break folder path up to determine which OS we are running");
			throw new E5xx_ActionFailed(__METHOD__);
		}
		$os = $parts[count($parts) - 2] . '-' . $parts[count($parts) - 1];

		$storytellerVarsFilename = dirname(dirname($pathToVmHomeFolder)) . "/ansible-playbooks/vars/storyteller.yml";

		// make sure we have something to write
		if (count($playbookVars) == 0) {
			$playbookVars['dummy'] = 'true';
		}

		// make sure we ahve somewhere to write it to
		$log->addStep("create folder for ansible vars file", function() use($storytellerVarsFilename) {
			$storytellerVarsDirname = dirname($storytellerVarsFilename);
			if (!is_dir($storytellerVarsDirname)) {
				mkdir($storytellerVarsDirname);
			}
		});

		// save the data
		$log->addStep("write vars to file '{$storytellerVarsFilename}'", function() use ($storytellerVarsFilename, $playbookVars) {
			file_put_contents($storytellerVarsFilename, yaml_emit($playbookVars));
		});

		// all done
		$log->endAction();
	}
}