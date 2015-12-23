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

namespace Storyplayer\SPv2\Modules\HTTP;

use DataSift\Stone\HttpLib\HttpClient;
use DataSift\Stone\HttpLib\HttpClientRequest;
use DataSift\Stone\HttpLib\HttpClientResponse;

use Prose\Prose;

use Storyplayer\SPv2\Modules\Exceptions;
use Storyplayer\SPv2\Modules\Log;

/**
 * do things to a web site by making requests directly to it (i.e. not
 * using the web browser at all)
 *
 * Great for testing APIs
 *
 * @category  Libraries
 * @package   Storyplayer/Modules/HTTP
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class UsingHttp extends Prose
{
    public function delete($url, $params = array(), $headers = array(), $timeout = null)
    {
        return $this->makeHttpRequest($url, "DELETE", $params, null, $headers, $timeout);
    }

    public function post($url, $params = array(), $body = null, $headers = array(), $timeout = null)
    {
        return $this->makeHttpRequest($url, "POST", $params, $body, $headers, $timeout);
    }

    public function put($url, $params = array(), $body = null, $headers = array(), $timeout = null)
    {
        return $this->makeHttpRequest($url, "PUT", $params, $body, $headers, $timeout);
    }

    /**
     * @param string $verb
     */
    protected function makeHttpRequest($url, $verb, $params, $body, $headers = array(), $timeout = null)
    {
        // create the full URL
        if (count($params) > 0) {
            $url = $url . '?' . http_build_query($params);
        }

        // what are we doing?
        $logMsg = [ "HTTP " . strtoupper($verb) . " '${url}'" ];
        if ($body != null) {
            $logMsg[] = $body;
        }
        if (count($headers) > 0) {
            $logMsg[] = $headers;
        }
        $log = Log::usingLog()->startAction($logMsg);

        // build the HTTP request
        $request = new HttpClientRequest($url);
        $request->withUserAgent("Storyplayer")
                ->withHttpVerb($verb);

        if (is_array($headers)) {
            foreach ($headers as $key => $value) {
                $request->withExtraHeader($key, $value);
            }
        }

        if (is_array($body)) {
            foreach ($body as $key => $value) {
                $request->addData($key, $value);
            }
        }else{
            $request->setPayload($body);
        }

        // special case - do we validate SSL certificates in this
        // test environment?
        $validateSsl = fromConfig()->getModuleSetting("http.validateSsl");
        if (null === $validateSsl) {
            // default to TRUE if no setting present
            $validateSsl = true;
        }
        if (!$validateSsl) {
            $request->disableSslCertificateValidation();
        }

        if ($timeout !== null) {
            $request->setReadTimeout($timeout);
        }

        // make the call
        $client = new HttpClient();
        $response = $client->newRequest($request);

        // is this a valid response?
        if (!$response instanceof HttpClientResponse) {
            throw Exceptions::newActionFailedException(__METHOD__);
        }

        // all done
        $log->endAction($response);
        return $response;
    }
}
