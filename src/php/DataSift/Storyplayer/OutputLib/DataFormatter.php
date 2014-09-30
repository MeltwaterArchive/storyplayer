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
 * @package   Storyplayer/OutputLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\OutputLib;

use DataSift\Stone\DataLib\DataPrinter;

/**
 * helper for producing printable PHP data
 *
 * @category  Libraries
 * @package   Storyplayer/OutputLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class DataFormatter
{
	private $isVerbose = false;

	public function getIsVerbose()
	{
		return $this->isVerbose;
	}

	public function setIsVerbose($isVerbose = true)
	{
		$this->isVerbose = $isVerbose;
	}

	public function convertData($data, $alwaysVerbose = false)
	{
		$printer = new DataPrinter();
		$logValue = $printer->convertToString($data);

		return $this->truncateIfRequired($logValue);
	}

	public function convertMessageArray($message)
	{
		$printer = new DataPrinter();
		$logValue = '';
		$parts = false;

		foreach ($message as $part) {
			$parts[] = $printer->convertToString($part);
		}

		$logValue = implode(' ', $parts);

		return $this->truncateIfRequired($logValue);
	}

	protected function truncateIfRequired($logValue, $alwaysVerbose = false)
	{
		$isVerbose = $alwaysVerbose;
		if (!$isVerbose) {
			$isVerbose = $this->isVerbose;
		}

		if (!$isVerbose && strlen($logValue) > 100) {
			$logValue = substr($logValue, 0, 100) . ' ...';
		}

		return $logValue;
	}
}
