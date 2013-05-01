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

/**
 * Helper class for describing the age of a time
 *
 * @category  Libraries
 * @package   Stone/TimeLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class When
{
    /**
     * singleton - cannot construct
     * @codeCoverageIgnore
     */
    private function __construct()
    {

    }

    /**
     * create a description of how old a timestamp is
     *
     * @param  int $when
     *         the timestamp to examine
     * @return string
     *         a description of how old the timestamp is
     */
    static public function age_asString($when)
    {
        $ageTime = time() - $when;

        // for things that have just happened
        if ($ageTime < 60)
        {
            return 'less than one minute';
        }

        // general case time - how old is this?
        $minutes = (int)($ageTime / 60)%60;
        $hours   = (int)($ageTime / 3600)%24;
        $days    = (int)($ageTime / 86400);

        $ranges = array
        (
            array ($days,     'day',    'days'),
            array ($hours,    'hour',   'hours'),
            array ($minutes,  'minute', 'minutes'),
        );

        $return = array();
        foreach ($ranges as $range)
        {
            self::expandTimeAge($return, $range[0], $range[1], $range[2]);
        }

        return join($return, ', ');
    }

    /**
     * helper method for expanding a single part of a date/time into a
     * description
     *
     * @param  array $return
     *         the array we add our results to
     * @param  int $count
     *         the number (of one of: days, hours, minutes) that we are examining
     * @param  string $single
     *         the correct description if $count == 1
     * @param  string $many
     *         the correct description if $count > 1
     * @return void
     */
    private static function expandTimeAge(&$return, $count, $single, $many)
    {
        if (count($return) && $count == 0)
        {
            return;
        }

        if ($count == 1)
        {
            $return[] = '1 ' . $single;
            return;
        }

        if ($count > 1)
        {
            $return[] = $count . ' ' . $many;
        }
    }
}