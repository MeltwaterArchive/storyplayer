<?php

namespace DataSift\Stone\TimeLib;

use PHPUnit_Framework_TestCase;

class DateIntervalTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers DataSift\Stone\TimeLib\DateInterval::__construct
	 */
	public function testCanInstantiate()
	{
	    // ----------------------------------------------------------------
	    // perform the change

		$obj = new DateInterval('P0D');

	    // ----------------------------------------------------------------
	    // test the results

		$this->assertTrue($obj instanceof \DataSift\Stone\TimeLib\DateInterval);
	}

	/**
	 * @covers DataSift\Stone\TimeLib\DateInterval::getTotalMinutes
	 */
	public function testCanGetTotalMinutes()
	{
	    // ----------------------------------------------------------------
	    // setup your test

		$expectedIntervals = array(
			'P300D' => 60 * 24 * 300,
			'PT300H' => 60 * 300,
			'PT300M' => 300,
			'PT360S' => 6,
			'PT350S' => 5
		);
		$actualIntervals = array();

		// ----------------------------------------------------------------
		// perform the change

		foreach ($expectedIntervals as $interval => $expectedMins)
		{
			$obj = new DateInterval($interval);
			$actualIntervals[$interval] = $obj->getTotalMinutes();
		}

	    // ----------------------------------------------------------------
	    // test the results

		$this->assertEquals($expectedIntervals, $actualIntervals);
	}

	/**
	 * @covers DataSift\Stone\TimeLib\DateInterval::getTotalSeconds
	 */
	public function testCanGetTotalSeconds()
	{
	    // ----------------------------------------------------------------
	    // setup your test

		$expectedIntervals = array(
			'P300D'  => 60 * 60 * 24 * 300,
			'PT300H' => 60 * 60 * 300,
			'PT300M' => 60 * 300,
			'PT360S' => 360
		);
		$actualIntervals = array();

		// ----------------------------------------------------------------
		// perform the change

		foreach ($expectedIntervals as $interval => $expectedMins)
		{
			$obj = new DateInterval($interval);
			$actualIntervals[$interval] = $obj->getTotalSeconds();
		}

	    // ----------------------------------------------------------------
	    // test the results

		$this->assertEquals($expectedIntervals, $actualIntervals);
	}
}
