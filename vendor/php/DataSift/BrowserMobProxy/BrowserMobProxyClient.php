<?php

/**
 * BrowserMobProxy - Client for browsermob-proxy
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @category  Libraries
 * @package   BrowserMobProxy
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2012 MediaSift Ltd.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://www.datasift.com
 */

namespace DataSift\BrowserMobProxy;

/**
 * Main gateway - used for creating browsermob-proxy sessions
 *
 * @category Libraries
 * @package  BrowserMobProxy
 * @author   Stuart Herbert <stuart.herbert@datasift.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0
 * @link     http://www.datasift.com
 */

class BrowserMobProxyClient extends BrowserMobProxyBase
{
    /**
     * Create a new proxy, running on an (optionally specified) port
     *
     * If the stated port is in use, browsermob-proxy will allocate the
     * next available port for you
     *
     * @param  integer $port the preferred port for the proxy
     *
     * @return BrowserMobProxySession a wrapper around the API for the
     *                                proxy instance that has been created
     */
    public function createProxy($port = null)
    {
        if ($port !== null) {
            // we want a specific port
            // willing to bet that this ends in tears!
            $response = $this->curl(
                'POST',
                '/proxy',
                array ('port' => $port)
            );
        }
        else {
            // we just want the next port available
            $response = $this->curl(
                'POST',
                '/proxy'
            );
        }

        // did it work?
        if (isset($response->port)) {
            // yes! return a session object to the caller
            $session = new BrowserMobProxySession($this->getUrl(), $response->port);
            return $session;
        }

        // if we get here, things went pear-shaped
        //
        // unfortunately, browsermob-proxy does not appear to support
        // a sane error results payload, so if this goes wrong, we're
        // a bit stuffed to understand why :(
        throw new E5xx_CannotCreateBrowserMobProxySession();
    }
}