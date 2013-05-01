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
 * Compares arrays against values of many types
 *
 * @category  Libraries
 * @package   Stone/ComparisonLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class ArrayComparitor extends ComparitorBase
{
	// ==================================================================
	//
	// Helper methods
	//
	// ------------------------------------------------------------------

	/**
	 * return a normalised version of the data, suitable for comparison
	 * purposes
	 *
	 * @return array
	 */
	public function getValueForComparison()
	{
		$return = $this->value;
		ksort($return);

		// sort any sub-arrays
		foreach ($return as $key => $value)
		{
			$comparitor   = $this->getComparitorFor($value);
			$return[$key] = $comparitor->getValueForComparison();
		}

		// all done
		return $return;
	}

	/**
	 * is the value we are testing the right type?
	 * @return boolean [description]
	 */
	public function isExpectedType()
	{
		// our return object
		$result = new ComparisonResult();

		// is it _really_ an array?
		if (!is_array($this->value)) {
			$result->setHasFailed("array", gettype($this->value));
			return $result;
		}

		// if we get here, all is good
		$result->setHasPassed();

		// all done
		return $result;
	}

	// ==================================================================
	//
	// The comparisons that this data type supports
	//
	// ------------------------------------------------------------------

	/**
	 * does this array contain the given value?
	 *
	 * @param  mixed $value the value to test for
	 * @return ComparisonResult
	 */
	public function containsValue($value)
	{
		// do we have a valid array to test?
		$result = $this->isExpectedType();
		if ($result->hasFailed()) {
			return $result;
		}

		// is the value present?
		if (!in_array($value, $this->value)) {
			$result->setHasFailed($value, "value not found");
			return $result;
		}

		// success
		return $result;
	}

	/**
	 * does this array NOT contain the given value?
	 *
	 * @param  mixed $value the value to test for
	 * @return ComparisonResult
	 */
	public function doesNotContainValue($value)
	{
		// do we have a valid array to test?
		$result = $this->isExpectedType();
		if ($result->hasFailed()) {
			return $result;
		}

		// is the value present?
		if (in_array($value, $this->value)) {
			$result->setHasFailed("value not found", $value);
			return $result;
		}

		// success
		return $result;
	}

	/**
	 * does this array contain the given key?
	 *
	 * @param  mixed $key the key to test for
	 * @return ComparisonResult
	 */
	public function hasKey($key)
	{
		// do we have a valid array to test?
		$result = $this->isExpectedType();
		if ($result->hasFailed()) {
			return $result;
		}

		// is the key present?
		if (!array_key_exists($key, $this->value)) {
			$result->setHasFailed("key '{$key}' set", "key does not exist");
			return $result;
		}

		// success
		return $result;
	}

	/**
	 * is the given key NOT in our array?
	 *
	 * @param  mixed $key the key to search for
	 * @return ComparisonResult
	 */
	public function doesNotHaveKey($key)
	{
		// do we have a valid array to test?
		$result = $this->isExpectedType();
		if ($result->hasFailed()) {
			return $result;
		}

		// is the key present?
		if (array_key_exists($key, $this->value)) {
			$result->setHasFailed("key not set", "key '{$key}' is set");
			return $result;
		}

		// success
		return $result;
	}

	/**
	 * is this array the length we expect it to be?
	 *
	 * @param  integer  $expected  the expected length of our array
	 * @return ComparisonResult
	 */
	public function hasLength($expected)
	{
		// are we looking at an array?
		$result = $this->isExpectedType();
		if ($result->hasFailed()) {
			return $result;
		}

		// how long is it?
		$actualLen = count($this->value);

		// is it the right length?
		if ($actualLen != $expected) {
			$result->setHasFailed("length of '{$expected}'", "length of '{$actualLen}'");
			return $result;
		}

		// if we get here, then it is the right length
		return $result;
	}

	/**
	 * is the value under test really an array?
	 *
	 * @return ComparisonResult
	 */
	public function isArray()
	{
		return $this->isExpectedType();
	}

	/**
	 * is our array the same length as another array?
	 *
	 * @param  array $expected
	 *         the array to compare against
	 * @return ComparisonResult
	 */
	public function isSameLengthAs($expected)
	{
		// are we looking at an array?
		$result = $this->isExpectedType();
		if ($result->hasFailed()) {
			return $result;
		}

		// are we comparing against an array?
		if (!is_array($expected)) {
			$type = gettype($expected);
			$result->setHasFailed("\$expected is an array", "\$expected is a '{$type}'");
			return $result;
		}

		// how long is our array?
		$actualLen = count($this->value);

		// how long is it supposed to be?
		$expectedLen = count($expected);

		// are they the same?
		if ($expectedLen != $actualLen) {
			$result->setHasFailed("length is '{$expectedLen}'", "length is '{$actualLen}'");
			return $result;
		}

		// success!
		return $result;
	}
}