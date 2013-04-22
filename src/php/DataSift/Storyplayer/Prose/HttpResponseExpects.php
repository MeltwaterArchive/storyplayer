<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\E5xx_ExpectFailed;
use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

use DataSift\Stone\DataLib\DataPrinter;
use DataSift\Stone\HttpLib\HttpClientResponse;

class HttpResponseExpects extends ProseActions
{
	/**
	 * DO NOT TYPEHINT $response!!
	 *
	 * Although it is extra work for us, if we use typehinting here, then
	 * we get a failure in the PHP engine (which is harder to handle).
	 * It's currently considered to be better if we detect the error
	 * ourselves.
	 *
	 * @param StoryTeller $st       [description]
	 * @param [type]      $response [description]
	 */
	public function __construct(StoryTeller $st, $params)
	{
		// do we HAVE a valid response?
		if (!isset($params[0])) {
			throw new E5xx_ExpectFailed(__METHOD__, "HttpClientResponse object", gettype($response));
		}
		$response = $params[0];

		if (!is_object($response)) {
			throw new E5xx_ExpectFailed(__METHOD__, "HttpClientResponse object", gettype($response));
		}
		if (!$response instanceof HttpClientResponse) {
			throw new E5xx_ExpectFailed(__METHOD__, "HttpClientResponse object", get_class($response));
		}

		// call our parent constructor
		parent::__construct($st, array($response));
	}

	public function hasStatusCode($status)
	{
		// shorthand
		$st = $this->st;
		$response = $this->args[0];

		// what are we doing?
		$log = $st->startAction("make sure HTTP response has status code '{$status}'");

		// do we even HAVE a response?
		if (!is_object($response)) {
			$log->endAction("no response to examine :(");
			throw new E5xx_ExpectFailed(__METHOD__, "HttpClientResponse object", gettype($response));
		}
		if (!$response instanceof HttpClientResponse) {
			$log->endAction("no response to examine :(");
			throw new E5xx_ExpectFailed(__METHOD__, "HttpClientResponse object", get_class($response));
		}

		if ($response->statusCode != $status) {
			$log->endAction("status code is '{$response->statusCode}'");
			throw new E5xx_ExpectFailed(__METHOD__, $status, $response->statusCode);
		}

		// if we get here, all is well
		$log->endAction();
	}

	public function hasBody($expectedBody)
	{
		// shorthand
		$st = $this->st;
		$response = $this->args[0];

		// make a printable version of $expectedBody
		$printer = new DataPrinter();
		$logValue = $printer->convertToString($expectedBody);

		if (strlen($logValue) > 1024) {
			$logValue = substr($logValue, 0, 1024) + '...';
		}

		// what are we doing?
		$log = $st->startAction("make sure HTTP response has body '{$logValue}'");

		// do the comparison
		$st->assertsString($response->getBody())->equals($expectedBody);

		// if we get here, all is well
		$log->endAction();
	}
}