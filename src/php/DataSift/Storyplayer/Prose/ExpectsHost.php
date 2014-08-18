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

/**
 *
 * test the state of a (possibly remote) computer
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class ExpectsHost extends HostBase
{
	public function hostIsRunning()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure host '{$this->args[0]}' is running");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// is it running?
		$running = $st->fromHost($hostDetails->name)->getHostIsRunning();
		if (!$running) {
			$log->endAction();
			throw new E5xx_ExpectFailed(__METHOD__, 'host is running', 'host is not running');
		}

		// all done
		$log->endAction();
	}

	public function hostIsNotRunning()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure host '{$this->args[0]}' is not running");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// is it running?
		$running = $st->fromHost($hostDetails->name)->getHostIsRunning();
		if ($running) {
			$log->endAction();
			throw new E5xx_ExpectFailed(__METHOD__, 'host is not running', 'host is running');
		}

		// all done
		$log->endAction();
	}

	public function packageIsInstalled($packageName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure package '{$packageName}' is installed on host '{$this->args[0]}'");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// is it installed?
		$details = $st->fromHost($hostDetails->name)->getInstalledPackageDetails($packageName);
		if (!isset($details->version)) {
			$log->endAction();
			throw new E5xx_ExpectFailed(__METHOD__, "package installed", "package is not installed");
		}

		// all done
		$log->endAction();
	}

	public function packageIsNotInstalled($packageName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure package '{$packageName}' is not installed on host '{$this->args[0]}'");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// is it installed?
		$details = $st->fromHost($hostDetails->name)->getInstalledPackageDetails($packageName);

		if (isset($details->version)) {
			$log->endAction();
			throw new E5xx_ExpectFailed(__METHOD__, "package not installed", "package is installed");
		}

		// all done
		$log->endAction();
	}

	public function processIsRunning($processName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure process '{$processName}' is running on host '{$this->args[0]}'");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// is the process running?
		$isRunning = $st->fromHost($hostDetails->name)->getProcessIsRunning($processName);

		if (!$isRunning) {
			throw new E5xx_ExpectFailed(__METHOD__, "process '{$processName}' running", "process '{$processName}' is not running");
		}

		// all done
		$log->endAction();
	}

	public function processIsNotRunning($processName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure process '{$processName}' is not running on host '{$this->args[0]}'");

		// make sure we have valid host details
		$hostDetails = $this->getHostDetails();

		// is the process running?
		$isRunning = $st->fromHost($hostDetails->name)->getProcessIsRunning($processName);

		if ($isRunning) {
			throw new E5xx_ExpectFailed(__METHOD__, "process '{$processName}' not running", "process '{$processName}' is running");
		}

		// all done
		$log->endAction();
	}

}
