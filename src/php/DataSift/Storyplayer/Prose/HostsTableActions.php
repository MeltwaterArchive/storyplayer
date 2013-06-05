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
use DataSift\Storyplayer\ProseLib\Prose;
use DataSift\Storyplayer\PlayerLib\StoryPlayer;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

use DataSift\Stone\ObjectLib\BaseObject;

/**
 * manipulate the internal hosts table
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class HostsTableActions extends Prose
{
	public function addHost($hostName, $hostDetails)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("add host '{$hostName}' to Storyplayer's hosts table");

		// get the runtime config
		$runtimeConfig = $st->getRuntimeConfig();

		// make sure we have a hosts table
		if (!isset($runtimeConfig->hosts)) {
			$runtimeConfig->hosts = new BaseObject();
		}

		// make sure we don't have a duplicate entry
		if (isset($runtimeConfig->hosts->$hostName)) {
			$msg = "Table already contains an entry for '{$hostName}'";
			$log->endAction($msg);
			throw new E5xx_ActionFailed(__METHOD__, $msg);
		}

		// add the entry
		$runtimeConfig->hosts->$hostName = $hostDetails;

		// save the updated runtimeConfig, in case Storyplayer terminates
		// with a fatal error at some point
		$log->addStep("saving runtime-config to disk", function() use($st, $runtimeConfig) {
			$configLoader = $st->getConfigLoader();
			$configLoader->saveRuntimeConfig($runtimeConfig);
		});

		// all done
		$log->endAction();
	}

	public function removeHost($hostName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("remove host '{$hostName}' from Storyplayer's hosts table");

		// get the runtime config
		$runtimeConfig = $st->getRuntimeConfig();

		// make sure we have a hosts table
		if (!isset($runtimeConfig->hosts)) {
			$msg = "Table is empty / does not exist";
			$log->endAction($msg);
			throw new E5xx_ActionFailed(__METHOD__, $msg);
		}

		// make sure we have an entry to remove
		if (!isset($runtimeConfig->hosts->$hostName)) {
			$msg = "Table does not contain an entry for '{$hostName}'";
			$log->endAction($msg);
			throw new E5xx_ActionFailed(__METHOD__, $msg);
		}

		// remove the entry
		unset($runtimeConfig->hosts->$hostName);

		// all done
		$log->endAction();
	}
}