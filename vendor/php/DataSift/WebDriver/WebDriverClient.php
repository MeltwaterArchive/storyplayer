<?php

/**
 * WebDriver - Client for Selenium 2 (a.k.a WebDriver)
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
 * @package   WebDriver
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2004-present Facebook
 * @copyright 2012-present MediaSift Ltd
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://www.datasift.com
 * @link      http://facebook.com
 */

namespace DataSift\WebDriver;

/**
 * Main client for interacting with the WebDriver
 *
 * @category Libraries
 * @package  WebDriver
 * @license  http://www.apache.org/licenses/LICENSE-2.0
 * @link     http://www.datasift.com
 * @link     http://facebook.com
 */
class WebDriverClient extends WebDriverBase
{
    protected function getMethods()
    {
        // a list of the JsonWireProtocol methods that this class supports
        return array(
          'status' => 'GET',
        );
    }

    /**
     * create a new session / launch a new browser to control
     *
     * @param  string $browser
     *                the name of the browser to use
     * @param  array  $additional_capabilities
     *                a list of the capabilities that are needed for this test
     *
     * @return WebDriverSession
     *         the new WebDriverSession to use to control the browser
     */
    public function newSession($browser = 'chrome', $additional_capabilities = array())
    {
        // merge the browser name into any additional requested capabilities
        $desired_capabilities = array_merge(
            $additional_capabilities,
            array('browserName' => $browser)
        );

        // tell webdriver to create the new session
        $results = $this->curl(
            'POST',
            '/session',
            array('desiredCapabilities' => $desired_capabilities),
            array(CURLOPT_FOLLOWLOCATION => true)
        );

        // return the session back to the caller
        return new WebDriverSession($results['info']['url']);
    }

    /**
     * get a list of active sessions
     *
     * @return array(WebDriverSession)
     *         a list of the sessions that webdriver currently knows about
     */
    public function getSessions()
    {
        // our return value
        $sessions = array();

        // get a raw list of the current sessions
        $result = $this->curl('GET', '/sessions');

        // convert the raw list into an array of WebDriverSession objects
        foreach ($result['value'] as $session) {
            $sessions[] = new WebDriverSession($this->url . '/session/' . $session['id']);
        }

        // return the full list of sessions
        return $sessions;
    }
}