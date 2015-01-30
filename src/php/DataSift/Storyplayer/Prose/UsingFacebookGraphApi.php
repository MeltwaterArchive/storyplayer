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
 * @author    Michael Heap <michael.heap@datasift.com>, Michael Pitidis <michael.pitidis@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\Prose\E5xx_ActionFailed;
use DataSift\Storyplayer\Prose\Prose;

/**
* get information from Facebook via the Graph API
*
* @category  Libraries
* @package   Storyplayer/Prose
* @author    Michael Heap <michael.heap@datasift.com>
* @copyright 2011-present Mediasift Ltd www.datasift.com
* @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
* @link      http://datasift.github.io/storyplayer
*/
class UsingFacebookGraphApi extends Prose
{

	protected $base_path = "https://graph.facebook.com";

	/**
	 * getPostsFromPage
	 *
	 * Get the first page of posts from a Facebook page
	 *
	 * @param int $id ID of the page to get data from
	 *
	 * @return array Posts from the page
	 */
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

	/**
	 * getLatestPostFromPage
	 *
	 * Get only the latest post from a page
	 *
	 * @param int $id ID of the page to get data from
	 *
	 * @return array First post from a page
	 */
	public function getLatestPostFromPage($id){
		$posts = $this->getPostsFromPage($id);
		return reset($posts);
	}

	public function getPostUpdatedTime($post_id) {
		return $this->st->fromCurl()->get("{$this->base_path}/{$post_id}", $this->addAccessToken(array(
			'fields' => 'updated_time',
		)));
	}

	public function createComment($post_id, $comment) {
		return $this->makeGraphPostRequest("/{$post_id}/comments", "message=$comment");
	}

	public function deleteComment($comment_id) {
		return $this->makeGraphDeleteRequest("/{$comment_id}");
	}

	private function makeGraphPostRequest($path, $data, $params = array()) {
		return $this->st->fromCurl()->post($this->base_path . $path, $data, $this->addAccessToken($params));
	}

	private function makeGraphDeleteRequest($path, $params = array()) {
		return $this->st->fromCurl()->delete($this->base_path . $path, $this->addAccessToken($params));
	}

	/**
	 * makeGraphApiRequest
	 *
	 * Make a request to the Graph API, including a user access token
	 *
	 * @param string $path URL to call in the graph API
	 *
	 * @return stdClass
	 */
	private function makeGraphApiRequest($path, $params = array()){
		$st = $this->st;

		$environment = $st->getEnvironment();

		$resp = $st->fromCurl()->get($this->base_path . $path, $this->addAccessToken($params));

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

	private function addAccessToken(array $params) {
		if (!isset($params['access_token'])) {
			$params['access_token'] = $this->st->getEnvironment()->facebookAccessToken;
		}
		return $params;
	}
}
