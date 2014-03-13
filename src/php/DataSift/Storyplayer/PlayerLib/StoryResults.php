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
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\PlayerLib;

use Exception;
use DataSift\Stone\ObjectLib\BaseObject;
use DataSift\Storyplayer\StoryLib\Story;

/**
 * a record of what happened with a story
 *
 * @category  Libraries
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class StoryResults
{
	const SETUP_SUCCESS       = 1;
	const SETUP_FAIL          = 2;

	const PREDICT_SUCCESS     = 10;
	const PREDICT_FAIL        = 11;
	const PREDICT_INCOMPLETE  = 12;
	const PREDICT_UNKNOWN     = 13;

	const ACTION_COMPLETED    = 20;
	const ACTION_FAILED       = 21;
	const ACTION_INCOMPLETE   = 22;
	const ACTION_UNKNOWN      = 23;
	const ACTION_HASNOACTIONS = 24;

	const INSPECT_SUCCESS     = 30;
	const INSPECT_FAIL        = 31;
	const INSPECT_INCOMPLETE  = 32;
	const INSPECT_UNKNOWN     = 33;

	const TEARDOWN_SUCCESS    = 40;
	const TEARDOWN_FAIL       = 41;

	const RESULT_PASS         = 100;
	const RESULT_FAIL         = 101;
	const RESULT_UNKNOWN      = 102;
	const RESULT_BLACKLISTED  = 103;

	static public $outcomeToText = array(
		1 => "Success",
		2 => "Fail",

		10 => "Success",
		11 => "Fail",
		12 => "Incomplete",
		13 => "Unknown",

		20 => "Completed",
		21 => "Failed",
		22 => "Incomplete",
		23 => "Unknown",
		24 => "Has No Actions",

		30 => "Success",
		31 => "Fail",
		32 => "Incomplete",
		33 => "Unkowwn",

		30 => "Success",
		31 => "Fail",

		100 => "Pass",
		101 => "Fail",
		102 => "Unknown",
		103 => "Blacklisted",
	);

	static public $defaultPhaseOutcomes = array(
		1 => self::SETUP_FAIL,
		2 => self::SETUP_FAIL,
		3 => self::PREDICT_UNKNOWN,
		4 => NULL,
		5 => self::ACTION_UNKNOWN,
		6 => self::INSPECT_UNKNOWN,
		7 => self::TEARDOWN_FAIL,
		8 => self::TEARDOWN_FAIL
	);
}
