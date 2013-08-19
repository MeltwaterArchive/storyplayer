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

use stdClass;

use Phix_Project\CliEngine;
use Phix_Project\CliEngine\CliResult;
use Phix_Project\CliEngine\CliSwitch;

use Phix_Project\ValidationLib4\Type_MustBeString;

/**
 * Tell Storyplayer which web browser to use in tests
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class WebBrowserSwitch extends CliSwitch
{
	public function __construct()
	{
		// define our name, and our description
		$this->setName('webbrowser');
		$this->setShortDescription('set the web browser to use in your tests');
		$this->setLongDesc(
			"If your stories use a web browser, use this switch to tell Storyplayer "
			."which web browser you want to use."
			. PHP_EOL . PHP_EOL
			."To avoid using this switch all the time, add the following to "
			."your environment config:"
			.PHP_EOL . PHP_EOL
			.'{' .PHP_EOL
			.'    "environments": {' . PHP_EOL
			.'        "defaults": {' . PHP_EOL
			.'            "webbrowser": "<browser>"' .PHP_EOL
			.'        }' . PHP_EOL
			.'    }'.PHP_EOL
			.'}'
			.PHP_EOL.PHP_EOL
			."This switch is an alias for '-Dwebbrowser=<browser>'."
		);

		// what are the short switches?
		$this->addShortSwitch('b');

		// what are the long switches?
		$this->addLongSwitch('web-browser');

		// what is the required argument?
		$this->setRequiredArg('<browser>', "the name of the browser to use (e.g. chrome, firefox, phantomjs)");
		$this->setArgValidator(new Type_MustBeString);
		$this->setArgHasDefaultValueOf('chrome');

		// all done
	}

	public function process(CliEngine $engine, $invokes = 1, $params = array(), $isDefaultParam = false)
	{
		// remember the setting
		if (!isset($engine->options->defines)) {
			$engine->options->defines = new stdClass;
		}
		// we do not remember when the default is used, because that would
		// override anything set in the config files
		if (!$isDefaultParam) {
			$engine->options->defines->webbrowser = $params[0];
		}

		// tell the engine that it is done
		return new CliResult(CliResult::PROCESS_CONTINUE);
	}
}