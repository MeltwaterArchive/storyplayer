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
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Cli;

use Phix_Project\CliEngine;
use Phix_Project\CliEngine\CliResult;
use Phix_Project\CliEngine\CliSwitch;

/**
 * Tell Storyplayer which browser / app config to use with testing
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Common_DeviceSwitch extends CliSwitch
{
	public function __construct($deviceList)
	{
		// define our name, and our description
		$this->setName('device');
		$this->setShortDescription('set the device (e.g. browser) to test with');
		$this->setLongDesc(
			"If you have multiple devices listed in your configuration files, "
			. "you can use this switch to choose which device to use when your test "
			. "runs. If you omit this switch, Storyplayer will default to using "
			. "your local copy of Google Chrome as the default device."
			. PHP_EOL
			. PHP_EOL
			. "See http://datasift.github.io/storyplayer/ "
			. "for how to configure and use multiple devices."
		);

		// what are the short switches?
		$this->addShortSwitch('d');
		$this->addShortSwitch('b');

		// what are the long switches?
		$this->addLongSwitch('device');
		$this->addLongSwitch('webbrowser');

		// do we have any devices defined?
		$msg = "the device to test with";
		$deviceNames = $deviceList->getEntryNames();
		if (count($deviceNames)) {
			$msg .= "; one of: " . implode(", ", $deviceNames);
		}
		else {
			// no devices found
			$msg .= ". You current have no devices listed in your config files.";
		}

		// what is the required argument?
		$this->setRequiredArg('<device>', $msg);

		// how do we validate this argument?
		$this->setArgValidator(new Common_DeviceValidator($deviceList));

		// chrome is our default device
		$this->setArgHasDefaultValueOf('chrome');

		// all done
	}

	/**
	 *
	 * @param  CliEngine $engine
	 * @param  integer   $invokes
	 * @param  array     $params
	 * @param  boolean   $isDefaultParam
	 * @return CliResult
	 */
	public function process(CliEngine $engine, $invokes = 1, $params = array(), $isDefaultParam = false)
	{
		// remember the setting
		$engine->options->device = $params[0];

		// tell the engine that it is done
		return new CliResult(CliResult::PROCESS_CONTINUE);
	}
}
