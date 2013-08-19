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
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\PlayerLib\StoryTeller;

/**
 * test the data stored in Graphite
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class GraphiteExpects extends Prose
{
	public function metricIsAlwaysZero($metric, $startTime, $endTime)
	{
		// shorthand
		$st = $this->st;

		// when are we looking for?
		$humanStartTime = date('Y-m-d H:i:s', $startTime);
		$humanEndTime   = date('Y-m-d H:i:s', $endTime);

		// what are we doing?
		$log = $st->startAction("ensure metric '{$metric}' is zero between '{$humanStartTime}' and '{$humanEndTime}'");

		// get the data from graphite
		$data = $st->fromGraphite()->getDataFor($metric, $startTime, $endTime);

		// do we *have* any data?
		if (empty($data) || !isset($data[0]->target, $data[0]->datapoints)) {
			// graphite returns an empty data set when there is no data
			//
			// NOTE: this can also happen when the test is asking for
			//       the wrong metric :(

			$log->endAction("no data available for metric '{$metric}'; assuming success");
			return;
		}

		// we have data ... let's make sure we're happy with it
		foreach ($data[0]->datapoints as $datapoint) {
			if ($datapoint[0] > 0) {
				throw new E5xx_ExpectFailed(__METHOD__, 0, $datapoint[0]);
			}
		}

		// all done
		$log->endAction("data was available, metric '{$metric}' was always zero");
		return;
	}

	public function metricSumIs($metric, $expectedTotal, $startTime, $endTime)
	{
		// shorthand
		$st = $this->st;

		// when are we looking for?
		$humanStartTime = date('Y-m-d H:i:s', $startTime);
		$humanEndTime   = date('Y-m-d H:i:s', $endTime);

		// what are we doing?
		$log = $st->startAction("ensure metric '{$metric}' sums to '{$expectedTotal}' between '{$humanStartTime}' and '{$humanEndTime}'");

		// get the data from graphite
		$data = $st->fromGraphite()->getDataFor($metric, $startTime, $endTime);

		// do we *have* any data?
		if (empty($data) || !isset($data[0]->target, $data[0]->datapoints)) {
			// graphite returns an empty data set when there is no data
			//
			// NOTE: this can also happen when the test is asking for
			//       the wrong metric :(
		 	if ($expectedTotal !== 0) {
		 		// we were expecting there to be some data
				throw new E5xx_ExpectFailed(__METHOD__, "data available for metric '{$metric}'", "no data available for metric '{$metric}'");
			}

			// if we get here, it's reasonable to assume that everything is
			// as it should be
			$log->endAction("no data available for metric '{$metric}'; assuming success");
			return;
		}

		// we have data ... let's make sure we're happy with it
		$actualTotal = 0;
		foreach ($data[0]->datapoints as $datapoint) {
			if ($datapoint[0] !== null) {
				$actualTotal += $datapoint[0];
			}
		}

		// do we have the total we expected?
		$st->assertsDouble($actualTotal)->equals($expectedTotal);

		// all done
		$log->endAction("data was available, metric '{$metric}' sums to '{$actualTotal}'");
		return;
	}

	public function metricNeverExceeds($metric, $expectedMax, $startTime, $endTime)
	{
		// shorthand
		$st = $this->st;

		// when are we looking for?
		$humanStartTime = date('Y-m-d H:i:s', $startTime);
		$humanEndTime   = date('Y-m-d H:i:s', $endTime);

		// what are we doing?
		$log = $st->startAction("ensure metric '{$metric}' never exceeds value '{$expectedMax}' between '{$humanStartTime}' and '{$humanEndTime}'");

		// get the data from graphite
		$data = $st->fromGraphite()->getDataFor($metric, $startTime, $endTime);

		// do we *have* any data?
		if (empty($data) || !isset($data[0]->target, $data[0]->datapoints)) {
			// graphite returns an empty data set when there is no data
			//
			// NOTE: this can also happen when the test is asking for
			//       the wrong metric :(
		 	if ($expectedTotal !== 0) {
		 		// we were expecting there to be some data
				throw new E5xx_ExpectFailed(__METHOD__, "no data available for metric '{$metric}'");
			}

			// if we get here, it's reasonable to assume that everything is
			// as it should be
			$log->endAction("no data available for metric '{$metric}'; assuming success");
			return;
		}

		// we have data ... let's make sure we're happy with it
		foreach ($data[0]->datapoints as $datapoint) {
			if ($datapoint[0] !== null) {
				$st->assertsDouble($datapoint[0])->isLessThanOrEqualTo($expectedMax);
			}
		}

		// all done
		$log->endAction("data was available, metric '{$metric}' never exceeds '{$expectedMax}'");
		return;
	}

	public function metricAverageDoesntExceed($metric, $expectedAverage, $startTime, $endTime)
	{
		// shorthand
		$st = $this->st;

		// when are we looking for?
		$humanStartTime = date('Y-m-d H:i:s', $startTime);
		$humanEndTime   = date('Y-m-d H:i:s', $endTime);

		// what are we doing?
		$log = $st->startAction("ensure metric '{$metric}' average never exceeds value '{$expectedMax}' between '{$humanStartTime}' and '{$humanEndTime}'");

		// get the data from graphite
		$data = $st->fromGraphite()->getDataFor($metric, $startTime, $endTime);

		// do we *have* any data?
		if (empty($data) || !isset($data[0]->target, $data[0]->datapoints)) {
			// graphite returns an empty data set when there is no data
			//
			// NOTE: this can also happen when the test is asking for
			//       the wrong metric :(
		 	if ($expectedTotal !== 0) {
		 		// we were expecting there to be some data
				throw new E5xx_ExpectFailed(__METHOD__, "no data available for metric '{$metric}'");
			}

			// if we get here, it's reasonable to assume that everything is
			// as it should be
			$log->endAction("no data available for metric '{$metric}'; assuming success");
			return;
		}

		// we have data ... let's make sure we're happy with it
		$total = 0;
		$count = 0;
		foreach ($data[0]->datapoints as $datapoint) {
			if ($datapoint[0] !== null) {
				$total += $datapoint[0];
				$count++;
			}
		}

		// what is the average?
		$average = $total/$count;

		// are we happy?
		$st->assertsDouble($average)->isLessThanOrEqualTo($expectedAverage);

		// all done
		$log->endAction("data was available, metric '{$metric}' never exceeds '{$expectedMax}'");
		return;
	}

}