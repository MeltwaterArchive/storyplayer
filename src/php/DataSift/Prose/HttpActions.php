<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;

use DataSift\Stone\HttpLib\HttpClient;
use DataSift\Stone\HttpLib\HttpClientRequest;
use DataSift\Stone\HttpLib\HttpClientResponse;

class HttpActions extends ProseActions
{
	public function delete($url, $params = array(), $body = null)
	{
		return $this->makeHttpRequest($url, "DELETE", $params, $body);
	}

	public function post($url, $params = array(), $body = null)
	{
		return $this->makeHttpRequest($url, "POST", $params, $body);
	}

	public function put($url, $params = array(), $body = null)
	{
		return $this->makeHttpRequest($url, "PUT", $params, $body);
	}

	protected function makeHttpRequest($url, $verb, $params, $body)
	{
		// shorthand
		$st = $this->st;

		// create the full URL
		if (count($params) > 0) {
			$url = $url . '?' . http_build_query($params);
		}

		// what are we doing?
		$log = $st->startAction("HTTP " . strtoupper($verb) . " '${url}'");

		// build the HTTP request
		$request = new HttpClientRequest($url);
		$request->withUserAgent("Storyplayer")
				->withHttpVerb($verb);

		if (is_array($body)) {
			foreach ($body as $key => $value) {
				$request->addData($key, $value);
			}
		}

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