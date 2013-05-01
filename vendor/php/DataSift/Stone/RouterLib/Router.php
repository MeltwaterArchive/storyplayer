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
 * @package   Stone/RouterLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */

namespace DataSift\Stone\RouterLib;

use DataSift\Stone\HtmlLib\FormData;
use DataSift\Stone\HttpLib\HttpData;
use DataSift\Stone\LogLib\Log;

/**
 * A very simple router to help work out where to send a request
 *
 * @category  Libraries
 * @package   Stone/RouterLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */

class Router
{
    /**
     * where is our application installed?
     * @var string
     */
    private $appDir = null;

    /**
     * constructor
     *
     * @param string $basedir
     *        where is our application installed?
     *
     *        we will look for controllers in {$basedir}/controllers/
     *        folder
     */
    public function __construct($basedir)
    {
        $this->appDir = $basedir;
    }

    /**
     * work out which controller to load for a specific HTTP request
     *
     * @param  Routes   $routes
     *         the list of routes that our app supports
     * @param  HttpData $get
     *         the HTTP data received via the query string
     * @param  FormData $post
     *         the HTTP data received via an encoded form posting
     * @return string
     *         the filename to require() to load the controller script
     */
    public function determineControllerForRequest(Routes $routes, HttpData $get, FormData $post)
    {
        // what is the current HTML verb?
        $verb = basename($_SERVER['REQUEST_METHOD']);

        // what are the possible routes to examine?
        $routesToCheck = $routes->getRoutesForVerb($verb);

        // what page did the customer try to access?
        $requestUri = $_SERVER['REQUEST_URI'];

        // note what is happening
        Log::write(Log::LOG_DEBUG, "Received " . $verb. " request for " . $requestUri);

        // find the controller to call
        foreach ($routesToCheck as $routeToCheck)
        {
            $matches = array();
            $pattern = '|^' . $routeToCheck['pattern'] . '$|';

            Log::write(Log::LOG_DEBUG, "Checking against route '{$pattern}'");

            if (!preg_match($pattern, $requestUri, $matches)) {
                // no match
                continue;
            }

            // if we get here, then we have found our route
            //
            // but does it exist?
            $requireFile = $this->appDir . '/controllers/' . $routeToCheck['controller'];

            // does the file exist?
            if (!file_exists($requireFile))
            {
                Log::write(Log::LOG_WARNING, "Missing front-end controller: " . $requireFile);
                $requireFile = $this->appDir . '/controllers/Error500.php';
            }

            // we need to add any matched URI parameters into our
            // data
            if ($verb == Routes::METHOD_GET) {
                foreach ($matches as $key => $value) {
                    $get->addData($key, $value);
                }
            }
            else if ($verb == Routes::METHOD_POST) {
                foreach ($matches as $key => $value) {
                    $post->addData($key, $value);
                }
            }

            // return the file we've decided on
            Log::write(Log::LOG_DEBUG, "Routing to script: " . $requireFile);
            return $requireFile;
        }

        // if we get here, then there's no matching route
        Log::write(Log::LOG_WARNING, "No matching route for request '{$requestUri}'");
        $requireFile = $this->appDir . '/controllers/Error404.php';

        // return the controller to run
        return $requireFile;
    }
}