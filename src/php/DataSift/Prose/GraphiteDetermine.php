<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

use DataSift\Stone\ObjectLib\JsonObject;

class GraphiteDetermine extends ProseActions
{
	public function getDataFor($metric, $startTime, $endTime)
	{
		// shorthand
		$st = $this->st;

		// when are we looking for?
		$humanStartTime = date('Y-m-d H:i:s', $startTime);
		$humanEndTime   = date('Y-m-d H:i:s', $endTime);

		// what are we doing?
		$log = $st->startAction("get raw data from graphite for '{$metric}' between '{$humanStartTime}' and '{$humanEndTime}'");

		// find out where graphite is
		$graphiteUrl = $st->fromEnvironment()->getGraphiteUrl();
		if (substr($graphiteUrl, -1, 1) !== '/') {
			$graphiteUrl .= '/';
		}

		// get the requested data
		$response = $st->fromHttp()->get("{$graphiteUrl}render?format=json&target={$metric}&from={$startTime}&until={$endTime}");

		// are there any stats in the response?
		$st->assertsArray($response->chunks)->isExpectedType();
		$st->assertsArray($response->chunks)->isNotEmpty();

		// assemble the raw chunks into one string to decode
		$rawStats = implode("", $response->chunks);
		$st->assertsString($rawStats)->isValidJson();
		$stats = json_decode($rawStats);

		// all done
		$log->endAction();
		return $stats;
	}
}