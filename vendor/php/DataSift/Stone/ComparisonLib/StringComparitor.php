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
 * Compares strings against various PHP data types
 *
 * @category  Libraries
 * @package   Stone/ComparisonLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class StringComparitor extends ComparitorBase
{
	// ==================================================================
	//
	// Helper methods
	//
	// ------------------------------------------------------------------

	/**
	 * is the data that we're examining a string, or convertable to a
	 * string?
	 *
	 * @return ComparisonResult
	 */
	public function isExpectedType()
	{
		// keep track of what happened
		$result = new ComparisonResult();

		$string = $this->value;

		if (is_string($string)) {
			$result->setHasPassed();
			return $result;
		}

		// force the type conversion
		$string = (string)$string;

		// now, do we still have a string?
		if (empty($string)) {
			$result->setHasFailed("string", gettype($this->value));
			return $result;
		}

		// all done
		$result->setHasPassed();
		return $result;
	}

	// ==================================================================
	//
	// The comparisons that this data type supports
	//
	// ------------------------------------------------------------------

	/**
	 * Does our string end with a given string?
	 *
	 * @param  string $expected the expected ending
	 * @return ComparisonResult
	 */
	public function endsWith($expected)
	{
		// do we have a string to test?
		$result = $this->isExpectedType();
		if ($result->hasFailed()) {
			return $result;
		}

		// is our string at least as long as the expected ending?
		if (strlen($this->value) < strlen($expected)) {
			$result->setHasFailed("ends with '{$expected}'", "string too short");
			return $result;
		}

		// get the ending
		$ending = substr($this->value, 0-strlen($expected));

		// is it what we expected?
		if ($ending != $expected) {
			$result->setHasFailed("ends with '{$expected}'", "ends with '{$ending}'");
			return $result;
		}

		// success
		return $result;
	}

	/**
	 * does our string NOT end with a given string?
	 *
	 * @param  string $expected the ending we do not expect
	 * @return ComparisonResult
	 */
	public function doesNotEndWith($expected)
	{
		// do we have a string to test?
		$result = $this->isExpectedType();
		if ($result->hasFailed()) {
			return $result;
		}

		// is our string at least as long as the expected ending?
		if (strlen($this->value) < strlen($expected)) {
			// string is too short, cannot possibly end with the expected string
			return $result;
		}

		// get the ending
		$ending = substr($this->value, 0-strlen($expected));

		// is it what we expected?
		if ($ending == $expected) {
			$result->setHasFailed("does not end with '{$expected}'", "ends with '{$ending}'");
			return $result;
		}

		// success - the endings are different
		return $result;
	}

	/**
	 * is the data that we're examining a hexadecimal string of some kind?
	 *
	 * @return ComparisonResult
	 */
	public function isHash()
	{
		// do we have a non-empty string to start off with?
		$result = $this->isNotEmpty();
		if ($result->hasFailed()) {
			return $result;
		}

		// let's make sure it is a hash
		$match = preg_match("/^[A-Za-z0-9]+$/", $this->value);
		if (!$match) {
			$result->setHasFailed("valid hex string", $this->value);
			return $result;
		}

		// let's make sure it's the right length
		$length = strlen($this->value);
		if ($length % 2 != 0) {
			$result->setHasFailed("valid hex string of even length", "string '{$this->value}' has odd length '{$length}'");
			return $result;
		}

		// success
		return $result;
	}

	/**
	 * does our string contain valid JSON?
	 *
	 * @return ComparisonResult
	 */
	public function isValidJson()
	{
		// do we have a string to start off with?
		$result = $this->isExpectedType();
		if ($result->hasFailed()) {
			return $result;
		}

		// convert from JSON
		$obj = json_decode((string)$this->value);

		// what happened?
		if (!is_object($obj) && !(is_array($obj))) {
			$result->setHasFailed("valid JSON string", "string failed to decode");
			return $result;
		}

		// if we get here, then success!
		return $result;
	}

	/**
	 * does our string NOT contain valid JSON?
	 *
	 * @return ComparisonResult
	 */
	public function isNotValidJson()
	{
		// does our string contain valid JSON?
		$result = $this->isValidJson();

		// negate the result
		if ($result->hasPassed()) {
			$result->setHasFailed("invalid JSON string", "valid JSON string");
		}
		else {
			$result->setHasPassed();
		}

		// all done
		return $result;
	}

	/**
	 * is our value under test really a string?
	 *
	 * @return ComparisonResult
	 */
	public function isString()
	{
		return $this->isExpectedType();
	}

	/**
	 * currently not implemented
	 *
	 * @param  string $regex
	 *         the PCRE regex to test against
	 * @return ComparisonResult
	 */
	public function matchesRegex($regex)
	{

	}

	/**
	 * currently not implemented
	 *
	 * @param  string $regex
	 *         the PCRE regex to test against
	 * @return ComparisonResult
	 */
	public function doesNotMatchRegex($regex)
	{

	}

	/**
	 * does our string start with an expected string?
	 *
	 * @param  string $expected the string we expect to start with
	 * @return ComparisonResult
	 */
	public function startsWith($expected)
	{
		// do we have a string to test?
		$result = $this->isExpectedType();
		if ($result->hasFailed()) {
			return $result;
		}

		// is our string at least as long as the expected ending?
		if (strlen($this->value) < strlen($expected)) {
			$result->setHasFailed("starts with '{$expected}'", "string too short");
			return $result;
		}

		// get the beginning
		$start = substr($this->value, 0, strlen($expected));

		// is it what we expected?
		if ($start != $expected) {
			$result->setHasFailed("starts with '{$expected}'", "starts with '{$start}'");
			return $result;
		}

		// success
		return $result;

	}

	/**
	 * does our string NOT start with an expected string?
	 *
	 * @param  string $expected the string we do NOT expect to start with
	 * @return ComparisonResult
	 */
	public function doesNotStartWith($expected)
	{
		// do we have a string to test?
		$result = $this->isExpectedType();
		if ($result->hasFailed()) {
			return $result;
		}

		// is our string at least as long as the expected ending?
		if (strlen($this->value) < strlen($expected)) {
			// string is too short, cannot possibly start with the expected string
			return $result;
		}

		// get the start
		$start = substr($this->value, 0, strlen($expected));

		// is it what we expected?
		if ($start == $expected) {
			$result->setHasFailed("does not start with '{$expected}'", "starts with '{$ending}'");
			return $result;
		}

		// success - the starts are different
		return $result;
	}
}