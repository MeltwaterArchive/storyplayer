<?php

namespace DataSift\Storyplayer\ApiLib;

use Exception;

class RestApiCall
{
	protected $username;
	protected $apikey;

	public function __construct($username, $apikey)
	{
		$this->username = $username;
		$this->apikey   = $apikey;
	}

	public function get($endpoint)
	{
		$curl = curl_init($endpoint);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt(
			$curl,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json;charset=UTF-8',
                'Accept: application/json',
                'Authorization: ' . $this->username . ':' . $this->apikey
            )
		);

		// make the request
		$rawResults = trim(curl_exec($curl));

		// find out what happened
		$info = curl_getinfo($curl);

		// did the curl call fail?
		if ($error = curl_error($curl)) {
			// 'fraid so
			$msg = sprintf(
				'Curl error thrown for http GET to %s: %s',
				$endpoint,
				$error
			);

			throw new Exception($msg);
		}

		// if we get here, then the server sent SOMETHING back
		curl_close($curl);
		$results = json_decode($rawResults);

		// did we get valid JSON back?
		if ($results === null) {
			// 'fraid not
			throw new Exception('Unable to decode JSON returned from ' . $endpoint);
		}

		// all done
		return $results;
	}
}