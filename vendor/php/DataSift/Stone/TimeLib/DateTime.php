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
 * @package   Stone/TimeLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */

namespace DataSift\Stone\TimeLib;

use DateTimeZone;

/**
 * Helper class for working with dates and times
 *
 * @category  Libraries
 * @package   Stone/TimeLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */

class DateTime extends \DateTime
{
    /**
     * constructor
     *
     * Takes an optional date/time offset to apply
     *
     * @param int $startTime
     *        the UNIX timestamp to apply (default is time())
     * @param string $offsetString
     *        any date/time offset to apply (must be valid DateInterval string)
     * @param string $timezone
     *        what timezone is $startTime in (default is UTC)
     */
    public function __construct($startTime = null, $offsetString = "P0D", $timezone = 'UTC')
    {
        // call the parent constructor
        parent::__construct();

        // do we have a time to apply?
        if ($startTime === null)
        {
            $startTime = time();
        }

        // apply the starting time
        $this->setTimezone(new DateTimeZone($timezone));
        $this->setTimestamp($startTime);

        // apply the offset
        $this->applyOffset($offsetString);

        // all done
    }

    /**
     * apply a date/time interval to this datetime
     *
     * @param  string $offsetString
     *         The dateinterval format string to apply.
     *         Can start with '-' if you want to subtract time.
     * @return void
     */
    public function applyOffset($offsetString)
    {
        // are we adding or subtracting?
        $sub = false;
        if ($offsetString{0} == '-') {
            // we are subtracting
            $sub = true;

            // remove the '-' from the front of the string, otherwise
            // Derick's DateTime::__construct() will complain
            $offsetString = substr($offsetString, 1);
        }

        // calculate the date/time interval
        $interval = new DateInterval($offsetString);

        // apply the offset string
        if ($sub) {
            $this->sub($interval);
        } else {
            $this->add($interval);
        }

        // all done
    }

    /**
     * get the date in the 'Y-m-d' format
     *
     * @return string
     */
    public function getDate()
    {
        return date('Y-m-d', $this->getTimestamp());
    }

    /**
     * return the date/time in the common 'Y-m-d H:i:s' format
     *
     * @return string
     */
    public function getDateTime()
    {
        return date('Y-m-d H:i:s', $this->getTimestamp());
    }

    /**
     * return a valid date/time string with the time set to midnight
     *
     * @return string
     */
    public function getDateTimeAtMidnight()
    {
        $return = $this->getDateTime();
        $return = substr($return, 0, 10) . " 00:00:00 " . $this->getTimezoneName();

        return $return;
    }

    /**
     * returns the number of seconds from this DateTime and the start of the month
     *
     * @return int
     */
    public function getSecondsSinceStartOfMonth()
    {
        $monthStartString = date('Y-m-01 00:00:00', $this->getTimestamp());
        $monthStartTime   = strtotime($monthStartString);
        $now = $this->getTimestamp();

        return $now - $monthStartTime;
    }

    /**
     * what timezone is this DateTime in?
     *
     * @return string
     */
    public function getTimezoneName()
    {
        return $this->getTimezone()->getName();
    }
}