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
 * @package   Stone/ComparisonLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */

namespace DataSift\Stone\ComparisonLib;

/**
 * Tracks the result of a comparison operation
 *
 * @category  Libraries
 * @package   Stone/ComparisonLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class ComparisonResult
{
	/**
	 * was the comparison successful (TRUE) or not (FALSE)
	 * @var boolean
	 */
	protected $passed = false;

	/**
	 * what was the expected value in the comparison?
	 *
	 * this value should be the value passed into one of the comparitor's
	 * methods
	 *
	 * @var mixed
	 */
	protected $expected = null;

	/**
	 * what was the actual value in the comparison?
	 *
	 * this value should be the value passed into the comparitor's
	 * constructor
	 *
	 * @var mixed
	 */
	protected $actual = null;

	/**
	 * did the comparison fail?
	 * @return boolean
	 */
	public function hasFailed()
	{
		return !$this->passed;
	}

	/**
	 * did the comparison succeed?
	 * @return boolean
	 */
	public function hasPassed()
	{
		return $this->passed;
	}

	/**
	 * mark the comparison as having failed
	 *
	 * @param mixed $expected
	 *        the value that we expected
	 * @param mixed $actual
	 *        the value that we actually got
	 */
	public function setHasFailed($expected, $actual)
	{
		$this->passed   = false;
		$this->expected = $expected;
		$this->actual   = $actual;

		return $this;
	}

	/**
	 * mark the comparison as having succeeded
	 *
	 * NOTE that, for a successful comparison, we do not store the values
	 * being compared. this information is only useful when comparisons
	 * fail
	 */
	public function setHasPassed()
	{
		$this->passed = true;

		return $this;
	}

	/**
	 * what was the expected value used in the failed comparison?
	 * @return mixed
	 */
	public function getExpected()
	{
		return $this->expected;
	}

	/**
	 * what was the actual value used in the failed comparison?
	 * @return mixed
	 */
	public function getActual()
	{
		return $this->actual;
	}
}