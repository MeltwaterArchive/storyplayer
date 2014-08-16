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

use DataSift\Storyplayer\Injectables;

use Exception;
use Phix_Project\CliEngine;
use Phix_Project\CliEngine\CliCommand;

/**
 * support for functionality that all commands are expected to support
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
trait CommonFunctionalitySupport
{
	public $commonFunctionality = [];

	public function initCommonFunctionalitySupport(CliCommand $command, $additionalContext, $options = null)
	{
		// make sure we have a list functionality to enable
		if (!$options) {
			$options = new DefaultCommonFunctionality;
		}

		// create the objects for each piece of functionality
		//
		// the order here determines the order that we process things in
		// after parsing the command line
		//
		// it is perfectly safe for anything in this list to rely on anything
		// that comes before it in the list
		foreach ($options->classes as $className) {
			$fullClassname = "DataSift\\Storyplayer\\Cli\\" . $className;
			$this->commonFunctionality[] = new $fullClassname;
		}

		// let each object register any switches that they need
		foreach ($this->commonFunctionality as $obj) {
			$obj->addSwitches($command, $additionalContext);
		}
	}

	public function applyCommonFunctionalitySupport(CliEngine $engine, CliCommand $command, Injectables $injectables)
	{
		try {
			// let's process the results of the CLI parsing that has already
			// happened
			foreach ($this->commonFunctionality as $obj) {
				$obj->initFunctionality($engine, $command, $injectables);
			}
		}
		catch (Exception $e) {
			// no matter what has gone wrong, we cannot continue
			$injectables->output->logCliError($e->getMessage());
			exit(1);
		}
	}
}
