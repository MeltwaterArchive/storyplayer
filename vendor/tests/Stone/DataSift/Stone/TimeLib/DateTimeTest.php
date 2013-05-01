<?php

namespace DataSift\Stone\TimeLib;

use PHPUnit_Framework_TestCase;

class DateTimeTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers DataSift\Stone\TimeLib\DateTime::__construct
	 */
	public function testCanInstantiate()
	{
	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new DateTime(time());

	    // ----------------------------------------------------------------
	    // test the results

		$this->assertTrue($obj instanceof \DataSift\Stone\TimeLib\DateTime);
	}

	/**
	 * @covers DataSift\Stone\TimeLib\DateTime::__construct
	 */
	public function testDefaultsToCurrentTimestamp()
	{
	    // ----------------------------------------------------------------
	    // setup your test
	    //
	    // explain your test setup here if needed ...

	    $now = time();
	    $expectedTimestamp = $now;

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new DateTime();

	    // ----------------------------------------------------------------
	    // test the results

	    $actualTimestamp = $obj->getTimestamp();
	    $this->assertEquals($expectedTimestamp, $actualTimestamp);
	}

	/**
	 * @covers DataSift\Stone\TimeLib\DateTime::__construct
	 */
	public function testCanInstantiateWithSpecifiedTimestamp()
	{
	    // ----------------------------------------------------------------
	    // setup your test
	    //
	    // explain your test setup here if needed ...

	    $now = time();
	    $expectedTimestamp = $now - 60;

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new DateTime($expectedTimestamp);

	    // ----------------------------------------------------------------
	    // test the results

	    $actualTimestamp = $obj->getTimestamp();
	    $this->assertEquals($expectedTimestamp, $actualTimestamp);
	}

	/**
	 * @covers DataSift\Stone\TimeLib\DateTime::__construct
	 */
	public function testCanInstantiateWithOffset()
	{
	    // ----------------------------------------------------------------
	    // setup your test
	    //
	    // explain your test setup here if needed ...

	    $now = time();
	    $expectedTimestamp = $now - 60;

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new DateTime($now, '-PT1M');

	    // ----------------------------------------------------------------
	    // test the results

	    $actualTimestamp = $obj->getTimestamp();
	    $this->assertEquals($expectedTimestamp, $actualTimestamp);
	}

	/**
	 * @covers DataSift\Stone\TimeLib\DateTime::applyOffset
	 */
	public function testCanApplyPositiveOffsetAfterInstantiation()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $now = time();
	    $expectedTimestamp = $now + 60;

	    $obj = new DateTime($now);
		$this->assertEquals($now, $obj->getTimestamp());

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->applyOffset('PT1M');

	    // ----------------------------------------------------------------
	    // test the results

	    $actualTimestamp = $obj->getTimestamp();
	    $this->assertEquals($expectedTimestamp, $actualTimestamp);
	}

	/**
	 * @covers DataSift\Stone\TimeLib\DateTime::applyOffset
	 */
	public function testCanApplyNegativeOffsetAfterInstantiation()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $now = time();
	    $expectedTimestamp = $now - 60;

	    $obj = new DateTime($now);
		$this->assertEquals($now, $obj->getTimestamp());

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->applyOffset('-PT1M');

	    // ----------------------------------------------------------------
	    // test the results

	    $actualTimestamp = $obj->getTimestamp();
	    $this->assertEquals($expectedTimestamp, $actualTimestamp);
	}

	/**
	 * @covers DataSift\Stone\TimeLib\DateTime::getDate
	 */
	public function testCanGetDate()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $now = time();
	    $expectedDate = date('Y-m-d', $now);

	    $obj = new DateTime($now);

	    // ----------------------------------------------------------------
	    // test the results

	    $actualDate = $obj->getDate();
	    $this->assertEquals($expectedDate, $actualDate);
	}

	/**
	 * @covers DataSift\Stone\TimeLib\DateTime::getDateTime
	 */
	public function testCanGetDateTime()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $now = time();
	    $expectedDate = date('Y-m-d H:i:s', $now);

	    $obj = new DateTime($now);

	    // ----------------------------------------------------------------
	    // test the results

	    $actualDate = $obj->getDateTime();
	    $this->assertEquals($expectedDate, $actualDate);
	}

	/**
	 * @covers DataSift\Stone\TimeLib\DateTime::getDateTimeAtMidnight
	 */
	public function testCanGetDateTimeAtMidnight()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $now = time();
	    $expectedDate = date('Y-m-d', $now) . ' 00:00:00 UTC';

	    $obj = new DateTime($now);

	    // ----------------------------------------------------------------
	    // test the results

	    $actualDate = $obj->getDateTimeAtMidnight();
	    $this->assertEquals($expectedDate, $actualDate);
	}

	/**
	 * @covers DataSift\Stone\TimeLib\DateTime::getSecondsSinceStartOfMonth
	 */
	public function testCanGetSecondsSinceStartOfMonth()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    // we need a fixed time to do this: 2012-12-04 15:16:17
	    $time = mktime(15, 16, 17, 12, 4, 2012);

	    // create the DateTime to work with
	    $obj = new DateTime($time);

	    // make sure we have the time that we are expecting
	    $expectedTimestamp = 1354634177;
	    $this->assertEquals($expectedTimestamp, $obj->getTimestamp());

	    // how many seconds do we expect?
	    $expectedSeconds = ((4 - 1) * 24 * 60 * 60)
	                     + (15 * 60 * 60)
	                     + (16 * 60)
	                     + 17;

	    // ----------------------------------------------------------------
	    // test the results

	    $actualSeconds = $obj->getSecondsSinceStartOfMonth();
	    $this->assertEquals($expectedSeconds, $actualSeconds);
	}

	/**
	 * @covers DataSift\Stone\TimeLib\DateTime::__construct
	 * @covers DataSift\Stone\TimeLib\DateTime::getTimezoneName
	 */
	public function testDefaultsToUTCTimezone()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new DateTime();
	    $expectedName = 'UTC';

	    // ----------------------------------------------------------------
	    // test the results

	    $actualName = $obj->getTimezoneName();
	    $this->assertEquals($expectedName, $actualName);
	}

	/**
	 * @covers DataSift\Stone\TimeLib\DateTime::getTimezoneName
	 */
	public function testCanGetTimezoneName()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $expectedName = 'Europe/London';
	    $obj = new DateTime(time(), 'P0D', $expectedName);

	    // ----------------------------------------------------------------
	    // test the results

	    $actualName = $obj->getTimezoneName();
	    $this->assertEquals($expectedName, $actualName);
	}

}
