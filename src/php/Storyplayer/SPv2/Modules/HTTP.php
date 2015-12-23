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
 * @package   Storyplayer/Modules/HTTP
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv2\Modules;

use DataSift\Stone\HttpLib\HttpClientResponse;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

use Storyplayer\SPv2\Modules\HTTP\ExpectsHttpResponse;
use Storyplayer\SPv2\Modules\HTTP\FromHttp;
use Storyplayer\SPv2\Modules\HTTP\UsingHttp;

class HTTP
{
    /**
     * returns the ExpectsHttpResponse module
     *
     * This module provides a great way to test that you got the response that
     * you expected from your usingHttp() / fromHttp() call.
     *
     * If the response doesn't meet your expectations, an exception will be
     * thrown.
     *
     * @param  HttpClientResponse $httpResponse
     *         the return value from usingHttp()->post() et al
     * @return ExpectsHttpResponse
     */
    public static function expectsHttpResponse(HttpClientResponse $httpResponse)
    {
        return new ExpectsHttpResponse(StoryTeller::instance(), [$httpResponse]);
    }

    /**
     * returns the FromHttp module
     *
     * This module provides support for making GET requests over HTTP.
     *
     * SSL/TLS is fully supported.
     *
     * If you are using self-signed certificates, you will need to set
     * 'moduleSettings.http.validateSsl = false' in your test environment's
     * config file first.
     *
     * To make PUT, POST and DELETE requests, use the UsingHttp module.
     *
     * @return FromHttp
     */
    public static function fromHttp()
    {
        return new FromHttp(StoryTeller::instance());
    }

    /**
     * returns the UsingHttp module
     *
     * This module provides support for making PUT, POST and DELETE requests
     * over HTTP (basically, any HTTP verb that should change state at the other
     * end).
     *
     * SSL/TLS is fully supported.
     *
     * If you are using self-signed certificates, you will need to set
     * 'moduleSettings.http.validateSsl = false' in your test environment's
     * config file first.
     *
     * To make GET requests, use the FromHttp module.
     *
     * @return UsingHttp
     */
    function usingHttp()
    {
        return new UsingHttp(StoryTeller::instance());
    }

}
