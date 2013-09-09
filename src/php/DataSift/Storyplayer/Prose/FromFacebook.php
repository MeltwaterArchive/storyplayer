<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\Prose\E5xx_ActionFailed;
use DataSift\Storyplayer\Prose\Prose;
use DataSift\ApiLib\RestApiCall;
use DataSift\Stone\ExceptionsLib\E5xx_NotImplemented;
use DataSift\Stone\ObjectLib\JsonObject;

class FromFacebook extends Prose
{

	protected $login_url = "https://www.facebook.com/login.php";
	protected $developer_url = "https://developers.facebook.com/tools/explorer";

	public function getAccessToken($options = array()){

		// Shorthand
		$st = $this->st;

		// Grab the details of our user
		$user = $this->args[0];

		// Start getting our token
		$disableCache = isset($options['disable_cache']) && $options['disable_cache'];

		// Get our runtime config
		$config = $st->getRuntimeConfig();

		// Check the one in the runtime config if we've not disabled the cache
		if (isset($config->facebookAccessToken, $config->facebookAccessToken->expires) && $config->facebookAccessToken->expires > time() && !$disableCache){
			return $config->facebookAccessToken->access_token;
		}

		// Login to Facebook
		$st->usingBrowser()->gotoPage($this->login_url);
		$st->usingBrowser()->type($user['email'])->intoFieldWithId('email');
		$st->usingBrowser()->type($user['password'])->intoFieldWithId('pass');
		$st->usingBrowser()->click()->fieldWithName('login');

		// Get our access token
		$tokenCreationTime = time();
		$st->usingBrowser()->gotoPage($this->developer_url);
		$access_token = $st->fromBrowser()->getValue()->fromFieldWithId('access_token');

		// Write it to the config
		$config->facebookAccessToken = array(
			"access_token" => $access_token,
			"expires" => ($tokenCreationTime + 5400) // It expires in 1.5 hours
		);
		$st->saveRuntimeConfig();

		return $access_token;
	}

}
