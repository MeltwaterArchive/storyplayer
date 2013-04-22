<?php

namespace DataSift\Storyplayer\Prose;

use Exception;

use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;

use DataSift\Stone\HttpLib\HttpClient;
use DataSift\Stone\HttpLib\HttpClientRequest;
use DataSift\Stone\HttpLib\HttpClientResponse;

class HttpDetermine extends ProseActions
{
	public function get($url, $params = array())
	{
		// shorthand
		$st = $this->st;

		// create the full URL
		if (count($params) > 0) {
			$url = $url . '?' . http_build_query($params);
		}

		// what are we doing?
		$log = $st->startAction("HTTP GET '${url}'");

		// build the HTTP request
		$request = new HttpClientRequest($url);
		$request->withUserAgent("Storyplayer")
		        ->asGetRequest();

		// make the call
		$client = new HttpClient();
		$response = $client->newRequest($request);

		// is this a valid response?
		if (!$response instanceof HttpClientResponse) {
			throw new E5xx_ActionFailed(__METHOD__);
		}

		// all done
		return $response;
	}
}