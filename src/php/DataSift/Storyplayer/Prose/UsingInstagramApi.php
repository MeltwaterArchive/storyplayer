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
 * get information from Instagram via its REST API
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Michael Pitidis <michael.pitidis@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class UsingInstagramApi extends Prose
{

    protected $base_path = "https://api.instagram.com/v1";


    /**
     * List a page of recent media around a specific area.
     *
     * @param float $lat latitude as a floating point number
     * @param float $lng longitude as a floating point number
     * @param int $distance search radious in meters
     * @param int $min_timestamp return media later than this
     * @param int $max_timestamp return media earlier than this
     * @return a page of recent media tagged in within that area.
     */
    public function searchMedia($lat, $lng, $distance = NULL, $min_timestamp = NULL, $max_timestamp = NULL)
    {
        $log = $this->st->startAction("search for media around $lat, $lng");
        $params = array(
            'lat' => $lat,
            'lng' => $lng,
            'distance' => $distance,
            'min_timestamp' => $min_timestamp,
            'max_timestamp' => $max_timestamp);
        $media = $this->makeApiRequest("/media/search", $params);
        $log->endAction("found media around $lat, $lng");
        return $media;
    }


    /**
     * List a page of recent media for a specific location.
     *
     * @param string $location_id the location to retrieve media for
     * @param string $min_id return media later than this
     * @param string $max_id return media earlier than this
     * @return a page of recent media for a specific location.
     */
    public function getRecentLocationMedia($location_id, $min_id = NULL, $max_id = NULL)
    {
        $log = $this->st->startAction("get recent media for location $location_id");
        $params = array('min_id' => $min_id, 'max_id' => $max_id);
        $media = $this->makeApiRequest("/locations/{$location_id}/media/recent", $params);
        $log->endAction("got media for location $location_id");
        return $media;
    }


    /**
     * List a page of recent media for a specific user.
     *
     * @param string $user_id the user to retrieve media for
     * @param string $min_id return media later than this
     * @param string $max_id return media earlier than this
     * @return a page of recent media for a specific user.
     */
    public function getRecentUserMedia($user_id, $min_id = NULL, $max_id = NULL)
    {
        $log = $this->st->startAction("get recent media for user $user_id");
        $params = array('min_id' => $min_id, 'max_id' => $max_id);
        $media = $this->makeApiRequest("/users/{$user_id}/media/recent", $params);
        $log->endAction("got media for user $user_id");
        return $media;
    }


    /**
     * @param int $count limit the number of entries returned
     * @return A list of popular media from Instagram.
     */
    public function getPopularMedia($count = NULL)
    {
        $st = $this->st;
        $log = $st->startAction("get popular media from Instagram");
        $params = array('count' => $count);
        $media = $this->makeApiRequest("/media/popular", $params);
        $log->endAction("got popular media from Instagram");
        return $media;
    }

    public function getMedia($media_id)
    {
        $log = $this->st->startAction("get media $media_id");
        $media = $this->makeApiRequest("/media/" . $media_id);
        $log->endAction("got media $media_id");
        return $media;
    }

    /**
     * Make an authenticated request to the Instagram API.
     * The access token is retrieved from the environment.
     *
     * @param string $path endpoint to call in the API
     * @return The list of items in the 'data' part of the response.
     */
    private function makeApiRequest($path, $params = array())
    {
        $st = $this->st;
        $env = $st->getEnvironment();

        $log = $st->startAction("performing authenticated Instagram API request");

        if (!isset($params['access_token']))
            $params['access_token'] = $env->instagram->accessToken;

        $resp = $st->fromCurl()->get(
            $this->base_path . $path . "?" . http_build_query($params));

        // Make sure it's well formed.
        if (!isset($resp->data)) {
            $str = json_encode($resp);
            $log->endAction("no 'data' key in payload '{$str}'");
            throw new E5xx_ActionFailed(__METHOD__, "Key 'data' was not found in response");
        }

        $size = sizeof($resp->data);
        $log->endAction("received $size items");

        return $resp->data;
    }
}

