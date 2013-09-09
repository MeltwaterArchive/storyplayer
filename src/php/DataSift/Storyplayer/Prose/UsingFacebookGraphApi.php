<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\Prose\E5xx_ActionFailed;
use DataSift\Storyplayer\Prose\Prose;
use DataSift\ApiLib\RestApiCall;
use DataSift\Stone\ExceptionsLib\E5xx_NotImplemented;
use DataSift\Stone\ObjectLib\JsonObject;

class UsingFacebookGraphApi extends Prose
{

	protected $base_path = "https://graph.facebook.com";

	public function getPostsFromPage($id)
	{
		// shorthand
		$st   = $this->st;

		// what are we doing?
		$log = $st->startAction("get posts from facebook for page {$id} via graph api");
		$returnedData = $this->makeGraphApiRequest("/".$id."/posts");
		$log->endAction("got posts for page {$id}");

		return $returnedData;
	}

	public function getLatestPostFromPage($id){
		$posts = $this->getPostsFromPage($id);
		return reset($posts);
	}

	private function makeGraphApiRequest($path){
		$st = $this->st;

		$environment = $st->getEnvironment();
		$access_token = $environment->facebookAccessToken;

		// GET $path/?access_token=$access_token
		$resp = $st->fromCurl()->get($this->base_path.$path.'?access_token='. $access_token);

		// Make sure it's well formed
		$log = $st->startAction("make sure we have the 'data' key in the response");
		if (!isset($resp->data)){

			// if it was an access token error, remove it from the runtime config
			if (isset($resp->error->message) && strpos($resp->error->message, "Error validating access token") !== false){
				// Remove the access token from the runtime config
				$config = $st->getRuntimeConfig();
				unset($config->facebookAccessToken);
				$st->saveRuntimeConfig();
			}

			$respString = json_encode($resp);
			$log->endAction("no data key found. payload is '{$respString}'");
			throw new E5xx_ActionFailed(__METHOD__, "Key 'data' was not found in response");
		}

		$log->endAction();

		return $resp->data;
	}

}
