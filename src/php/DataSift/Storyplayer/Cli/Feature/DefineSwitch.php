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
use Phix_Project\ValidationLib4\Type_MustBeKeyValuePair;

/**
 * Override the settings defined in your story
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Feature_DefineSwitch extends CliSwitch
{
	public function __construct()
	{
		// define our name, and our description
		$this->setName('define');
		$this->setShortDescription('override a setting in your story');

		// what are the short switches?
		$this->addShortSwitch('D');

		// what is the required argument?
		$this->setRequiredArg('<key=value>', "the setting you want to set in your story");
		$this->setArgValidator(new Type_MustBeKeyValuePair);

		// this argument is repeatable
		$this->setSwitchIsRepeatable();

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
		if (!isset($engine->options->defines)) {
			$engine->options->defines = new stdClass;
		}

		foreach ($params as $param)
		{
			// split up the setting
			$parts = explode('=', $param);
			$key   = array_shift($parts);
			$value = implode('=', $parts);

			// do we want to convert the type of $value?
			$lowerValue = strtolower($value);
			if ($lowerValue == 'false') {
				$value = false;
			}
			else if ($lowerValue == 'true') {
				$value = true;
			}
			else if ($lowerValue == 'null') {
				$value = null;
			}

			// expand dot notation
			$parts = explode('.', $key);
			$currentLevel = $engine->options->defines;
			$lastPart = array_pop($parts);

			foreach ($parts as $part) {
				$currentLevel->$part = new stdClass;
				$currentLevel = $currentLevel->$part;
			}
			// store the value into the tree
			$currentLevel->$lastPart = $value;
		}

		// tell the engine that it is done
		return new CliResult(CliResult::PROCESS_CONTINUE);
	}
}