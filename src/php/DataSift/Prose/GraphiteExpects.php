<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

use DataSift\Storyplayer\ProseLib\E5xx_ExpectFailed;

class GraphiteExpects extends ProseActions
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

	public function metricAverageIsLessThanOrEqualTo($metric, $expectedAverage, $startTime, $endTime)
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