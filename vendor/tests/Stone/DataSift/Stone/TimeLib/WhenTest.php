<?php

namespace DataSift\Stone\TimeLib;

use PHPUnit_Framework_TestCase;
use ReflectionClass;

class WhenTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers DataSift\Stone\TimeLib\When::__construct
	 */
	public function testCannotInstantiate()
	{
	    // ----------------------------------------------------------------
	    // perform the change

		$refClass = new ReflectionClass('DataSift\Stone\TimeLib\When');
		$refMethod = $refClass->getMethod('__construct');

		// ----------------------------------------------------------------
		// test the results

		$this->assertFalse($refMethod->isPublic());
	}

	/**
	 * @covers DataSift\Stone\TimeLib\When::age_asString
	 * @covers DataSift\Stone\TimeLib\When::expandTimeAge
	 */
	public function testHandlesZeroAgeAsSpecialCase()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $expectedAge = 'less than one minute';

	    // ----------------------------------------------------------------
	    // perform the change

    	$actualAge = When::age_asString(time());

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expectedAge, $actualAge);
	}

	/**
	 * @covers DataSift\Stone\TimeLib\When::age_asString
	 * @covers DataSift\Stone\TimeLib\When::expandTimeAge
	 */
	public function testCanGetAgeInDays()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $expectedAges = array(
	    	'P0D' => 'less than one minute',
	    	'P1D' => '1 day',
	    	'P5D' => '5 days'
	    );
	    $actualAges = array();

	    // ----------------------------------------------------------------
	    // perform the change

	    foreach ($expectedAges as $interval => $expectedAge)
	    {
	    	$intervalObj = new DateInterval($interval);
	    	$actualAges[$interval] = When::age_asString(time() - $intervalObj->getTotalSeconds());
	    }

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expectedAges, $actualAges);
	}

	/**
	 * @covers DataSift\Stone\TimeLib\When::age_asString
	 * @covers DataSift\Stone\TimeLib\When::expandTimeAge
	 */
	public function testCanGetAgeInHours()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $expectedAges = array(
	    	'P0D' => 'less than one minute',
	    	'PT1H' => '1 hour',
	    	'PT5H' => '5 hours'
	    );
	    $actualAges = array();

	    // ----------------------------------------------------------------
	    // perform the change

	    foreach ($expectedAges as $interval => $expectedAge)
	    {
	    	$intervalObj = new DateInterval($interval);
	    	$actualAges[$interval] = When::age_asString(time() - $intervalObj->getTotalSeconds());
	    }

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expectedAges, $actualAges);
	}

	/**
	 * @covers DataSift\Stone\TimeLib\When::age_asString
	 * @covers DataSift\Stone\TimeLib\When::expandTimeAge
	 */
	public function testCanGetAgeInMinutes()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $expectedAges = array(
	    	'P0D' => 'less than one minute',
	    	'PT1M' => '1 minute',
	    	'PT5M' => '5 minutes'
	    );
	    $actualAges = array();

	    // ----------------------------------------------------------------
	    // perform the change

	    foreach ($expectedAges as $interval => $expectedAge)
	    {
	    	$intervalObj = new DateInterval($interval);
	    	$actualAges[$interval] = When::age_asString(time() - $intervalObj->getTotalSeconds());
	    }

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expectedAges, $actualAges);
	}

	/**
	 * @covers DataSift\Stone\TimeLib\When::age_asString
	 * @covers DataSift\Stone\TimeLib\When::expandTimeAge
	 */
	public function testCanGetAgeInDaysHoursAndMinutes()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $expectedAges = array(
			'P0D'      => 'less than one minute',
			'PT1H2M'   => '1 hour, 2 minutes',
			'P1DT2H3M' => '1 day, 2 hours, 3 minutes',
			'P5DT9M'   => '5 days, 9 minutes',
			'P7DT4H'   => '7 days, 4 hours'
	    );
	    $actualAges = array();

	    // ----------------------------------------------------------------
	    // perform the change

	    foreach ($expectedAges as $interval => $expectedAge)
	    {
	    	$intervalObj = new DateInterval($interval);
	    	$actualAges[$interval] = When::age_asString(time() - $intervalObj->getTotalSeconds());
	    }

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expectedAges, $actualAges);
	}

	/**
	 * @covers DataSift\Stone\TimeLib\When::age_asString
	 * @covers DataSift\Stone\TimeLib\When::expandTimeAge
	 */
	public function testSupportsSingleAndPluralDays()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $expectedAges = array(
	    	'P1D' => '1 day',
	    );
	    for ($i = 2; $i < 365; $i++)
	    {
			$key   = "P{$i}D";
			$value = "{$i} days";
	    	$expectedAges[$key] = $value;
	    }
	    $actualAges = array();

	    // ----------------------------------------------------------------
	    // perform the change

	    foreach ($expectedAges as $interval => $expectedAge)
	    {
	    	$intervalObj = new DateInterval($interval);
	    	$actualAges[$interval] = When::age_asString(time() - $intervalObj->getTotalSeconds());
	    }

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expectedAges, $actualAges);
	}

	/**
	 * @covers DataSift\Stone\TimeLib\When::age_asString
	 * @covers DataSift\Stone\TimeLib\When::expandTimeAge
	 */
	public function testSupportsSingleAndPluralHours()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $expectedAges = array(
	    	'PT1H' => '1 hour'
	    );
	    for ($i = 2; $i < 24; $i++)
	    {
			$key   = "PT{$i}H";
			$value = "{$i} hours";
	    	$expectedAges[$key] = $value;
	    }
	    $actualAges = array();

	    // ----------------------------------------------------------------
	    // perform the change

	    foreach ($expectedAges as $interval => $expectedAge)
	    {
	    	$intervalObj = new DateInterval($interval);
	    	$actualAges[$interval] = When::age_asString(time() - $intervalObj->getTotalSeconds());
	    }

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expectedAges, $actualAges);
	}

	/**
	 * @covers DataSift\Stone\TimeLib\When::age_asString
	 * @covers DataSift\Stone\TimeLib\When::expandTimeAge
	 */
	public function testSupportsSingleAndPluralMinutes()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $expectedAges = array(
	    	'PT1M' => '1 minute',
	    );
	    for ($i = 2; $i < 60; $i++)
	    {
			$key   = "PT{$i}M";
			$value = "{$i} minutes";
	    	$expectedAges[$key] = $value;
	    }
	    $actualAges = array();

	    // ----------------------------------------------------------------
	    // perform the change

	    foreach ($expectedAges as $interval => $expectedAge)
	    {
	    	$intervalObj = new DateInterval($interval);
	    	$actualAges[$interval] = When::age_asString(time() - $intervalObj->getTotalSeconds());
	    }

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expectedAges, $actualAges);
	}

}
