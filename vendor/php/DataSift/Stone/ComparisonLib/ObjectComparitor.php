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

use DataSift\Stone\TypeLib\TypeConvertor;

/**
 * Compares objects against other data types
 *
 * @category  Libraries
 * @package   Stone/ComparisonLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class ObjectComparitor extends ComparitorBase
{
	// ==================================================================
	//
	// Helper methods
	//
	// ------------------------------------------------------------------

	/**
	 * return a normalised version of the data, suitable for comparison
	 * @return stdClass
	 */
	public function getValueForComparison()
	{
		// we need to turn our object into an array, so that we can
		// force an order to the result
		$intermediate = array();

		// fill out our array with our normalised data
		foreach ($this->value as $key => $value) {
			$comparitor = $this->getComparitorFor($value);
			$intermediate[$key] = $comparitor->getValueForComparison();
		}

		// sort the array, to make comparison sane
		ksort($intermediate);

		// all done - return it as a stdClass
		return (object)$intermediate;
	}

	/**
	 * is the value we are testing the right type?
	 * @return ComparisonResult
	 */
	public function isExpectedType()
	{
		// the result that we will return
		$result = new ComparisonResult();

		// is this really an object?
		if (!is_object($this->value)) {
			$result->setHasFailed("object", gettype($this->value));
		}
		else {
			$result->setHasPassed();
		}

		// all done
		return $result;
	}

	// ==================================================================
	//
	// The comparisons that this data type supports
	//
	// ------------------------------------------------------------------

	/**
	 * Does our object have the given attribute?
	 *
	 * @param  string  $attribute  the name of the attribute to test for
	 * @return ComparisonResult
	 */
	public function hasAttribute($attribute)
	{
		// do we have an actual object?
		$result = $this->isExpectedType();
		if ($result->hasFailed()) {
			return $result;
		}

		// does the attribute exist?
		if (!isset($this->value->$attribute)) {
			$result->setHasFailed("has attribute '{$attribute}'", "does not have attribute '{$attribute}'");
			return $result;
		}

		// if we get here, then the object passes the test
		return $result;
	}

	/**
	 * Does our object NOT have the given attribute?
	 *
	 * @param  string $attribute  the name of the attribute to test for
	 * @return ComparisonResult
	 */
	public function doesNotHaveAttribute($attribute)
	{
		// do we have an actual object?
		$result = $this->isExpectedType();
		if ($result->hasFailed()) {
			return $result;
		}

		// does the attribute exist?
		if (isset($this->value->$attribute)) {
			$result->setHasFailed("does not have attribute '{$attribute}'", "has attribute '{$attribute}'");
			return $result;
		}

		// if we get here, then the object passes the test
		$result->setHasPassed();
		return $result;

	}

	/**
	 * does our object under test have an attribute with a given name, and
	 * does that attribute have the given value?
	 *
	 * @param  string  $attribute name of the attribute to test
	 * @param  mixed   $value     the expected value of the attribute
	 * @return ComparisonResult
	 */
	public function hasAttributeWithValue($attribute, $value)
	{
		// do we have an actual object?
		$result = $this->isExpectedType();
		if ($result->hasFailed()) {
			return $result;
		}

		// does the attribute exist?
		if (!isset($this->value->$attribute)) {
			$result->setHasFailed("attribute '{$attribute}' with value '{$value}'", "attribute does not exist");
			return $result;
		}

		// compare the values of the two
		$comparitor = $this->getComparitorFor($this->value->attribute);
		$result = $comparitor->equals($value);

		// all done
		return $result;
	}

	/**
	 * does our object under test have an attribute with a given name, and
	 * does that attribute NOT have the given value?
	 *
	 * @param  string  $attribute name of the attribute to test
	 * @param  mixed   $value     the unexpected value of the attribute
	 * @return ComparisonResult
	 */
	public function doesNotHaveAttributeWithValue($attribute, $value)
	{
		// do we have an actual object?
		$result = $this->isExpectedType();
		if ($result->hasFailed()) {
			return $result;
		}

		// does the attribute exist?
		if (!isset($this->value->$attribute)) {
			$result->setHasFailed("attribute '{$attribute}' exists, not with value '{$value}'", "attribute does not exist");
			return $result;
		}

		// compare the values of the two
		$comparitor = $this->getComparitorFor($this->value->attribute);
		$result = $comparitor->doesNotEqual($value);

		// all done
		return $result;
	}

	/**
	 * does the object under test have a given method name?
	 *
	 * @param  string  $methodName the method to test for
	 * @return ComparisonResult
	 */
	public function hasMethod($methodName)
	{
		// do we have an actual object?
		$result = $this->isExpectedType();
		if ($result->hasFailed()) {
			return $result;
		}

		// does the method exist?
		if (!method_exists($this->value, $methodName)) {
			$result->setHasFailed("method '{$methodName}' exists", "method does not exist");
			return $result;
		}

		// success
		return $result;
	}

	/**
	 * does the object under test have a given method name?
	 *
	 * @param  string  $methodName the method to test for
	 * @return ComparisonResult
	 */
	public function doesNotHaveMethod($methodName)
	{
		// do we have an actual object?
		$result = $this->isExpectedType();
		if ($result->hasFailed()) {
			return $result;
		}

		// does the method exist?
		if (method_exists($this->value, $methodName)) {
			$result->setHasFailed("method '{$methodName}' does not exist", "method exists");
			return $result;
		}

		// success
		return $result;
	}

	/**
	 * is the object under test an instance of a specific class?
	 *
	 * @param  string  $className the class name to test for
	 * @return ComparisonResult
	 */
	public function isInstanceOf($className)
	{
		// do we have an actual object?
		$result = $this->isExpectedType();
		if ($result->hasFailed()) {
			return $result;
		}

		// are we an instance of the named class?
		if (!$this->value instanceof $className) {
			$result->setHasFailed("instance of '{$className}'", "not an instance of '{$className}'");
			return $result;
		}

		// success
		return $result;

	}

	/**
	 * is the object under test NOT an instance of a specific class?
	 *
	 * @param  string  $className the class name to test for
	 * @return ComparisonResult
	 */
	public function isNotInstanceOf($className)
	{
		// do we have an actual object?
		$result = $this->isExpectedType();
		if ($result->hasFailed()) {
			return $result;
		}

		// are we an instance of the named class?
		if ($this->value instanceof $className) {
			$result->setHasFailed("not an instance of '{$className}'", "instance of '{$className}'");
			return $result;
		}

		// success
		return $result;
	}

	/**
	 * is the object under test really an object?
	 *
	 * @return ComparisonResult
	 */
	public function isObject()
	{
		return $this->isExpectedType();
	}
}