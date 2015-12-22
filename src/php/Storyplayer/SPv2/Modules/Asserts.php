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
 * @package   Storyplayer
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv2\Modules;

use DataSift\Storyplayer\PlayerLib\StoryTeller;

// ==================================================================
//
// a list of all the Prose modules that we are exposing
//
// this file registers global functions for each of our Prose modules
// it is a great help for everyone who uses autocompletion in their
// editor / IDE of choice
//
// keep this list in alphabetical order, please!
//
// ------------------------------------------------------------------

use Storyplayer\SPv2\Modules\Asserts\AssertsArray;
use Storyplayer\SPv2\Modules\Asserts\AssertsBoolean;
use Storyplayer\SPv2\Modules\Asserts\AssertsDouble;
use Storyplayer\SPv2\Modules\Asserts\AssertsInteger;
use Storyplayer\SPv2\Modules\Asserts\AssertsObject;
use Storyplayer\SPv2\Modules\Asserts\AssertsString;

class Asserts
{
    /**
     * returns the AssertsArray module
     *
     * @param  array $actual
     *         the array to be tested
     * @return \Prose\AssertsArray
     */
    public static function assertsArray($actual)
    {
        return new AssertsArray(StoryTeller::instance(), [$actual]);
    }

    /**
     * returns the AssertsBoolean module
     *
     * @param  boolean $actual
     *         the data to be tested
     * @return \Prose\AssertsBoolean
     */
    public static function assertsBoolean($actual)
    {
        return new AssertsBoolean(StoryTeller::instance(), [$actual]);
    }

    /**
     * returns the AssertsDouble module
     *
     * @param  double $actual
     *         the data to be tested
     * @return \Prose\AssertsDouble
     */
    public static function assertsDouble($actual)
    {
        return new AssertsDouble(StoryTeller::instance(), [$actual]);
    }

    /**
     * returns the AssertsInteger module
     *
     * @param  int $actual
     *         the data to be tested
     * @return \Prose\AssertsInteger
     */
    public static function assertsInteger($actual)
    {
        return new AssertsInteger(StoryTeller::instance(), [$actual]);
    }

    /**
     * returns the AssertsObject module
     *
     * @param  object $actual
     *         the data to be tested
     * @return \Prose\AssertsObject
     */
    public static function assertsObject($actual)
    {
        return new AssertsObject(StoryTeller::instance(), [$actual]);
    }

    /**
     * returns the AssertsString module
     *
     * @param  string $actual
     *         the data to be tested
     * @return \Prose\AssertsString
     */
    public static function assertsString($actual)
    {
        return new AssertsString(StoryTeller::instance(), [$actual]);
    }
}
