<?php

namespace DataSift\Storyplayer\Prose;

use Exception;

use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\ProseLib\E5xx_ExpectFailed;

use DataSift\Stone\HttpLib\HttpClientResponse;

class HttpExpects extends ProseActions
{
	public function get($url, $params = array())
	{
		// shorthand
		$st = $this->st;

		// make the call
		$response = $st->fromHttp()->get($url, $params);

		// did it work?
		if (!$response instanceof HttpClientResponse) {
			throw new E5xx_ExpectFailed(__METHOD__, "HttpClientResponse", get_class($response));
		}
	}
}