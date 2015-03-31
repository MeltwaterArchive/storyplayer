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
 * @author    Michael Heap <michael.heap@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Prose;

/**
* get information from Facebook
*
* @category  Libraries
* @package   Storyplayer/Prose
* @author    Michael Heap <michael.heap@datasift.com>
* @copyright 2011-present Mediasift Ltd www.datasift.com
* @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
* @link      http://datasift.github.io/storyplayer
*/
class FromFacebook extends Prose
{

    protected $login_url = "https://www.facebook.com/login.php";
    protected $developer_url = "https://developers.facebook.com/tools/explorer";

    /**
     * getAccessToken
     *
     * @param array $options Options to use when getting token
     *
     * @return string Access token
     */
    public function getAccessToken($options = array()){

        // Grab the details of our user
        $user = $this->args[0];

        // Start getting our token
        $disableCache = isset($options['disable_cache']) && $options['disable_cache'];

        // Get our runtime config
        $config = $this->st->getRuntimeConfig();

        // Check the one in the runtime config if we've not disabled the cache
        if (isset($config->facebookAccessToken, $config->facebookAccessToken->expires) && $config->facebookAccessToken->expires > time() && !$disableCache){
            return $config->facebookAccessToken->access_token;
        }

        // Login to Facebook
        usingBrowser()->gotoPage($this->login_url);
        usingBrowser()->type($user['email'])->intoFieldWithId('email');
        usingBrowser()->type($user['password'])->intoFieldWithId('pass');
        usingBrowser()->click()->fieldWithName('login');

        // Get our access token
        $tokenCreationTime = time();
        usingBrowser()->gotoPage($this->developer_url);
        $access_token = fromBrowser()->getValue()->fromFieldWithId('access_token');

        // Write it to the config
        $config->facebookAccessToken = array(
            "access_token" => $access_token,
            "expires" => ($tokenCreationTime + 5400) // It expires in 1.5 hours
        );
        $this->st->saveRuntimeConfig();

        return $access_token;
    }

}
