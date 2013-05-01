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

use stdClass;

/**
 * Base class for all classes that talk to browsermob-proxy
 *
 * @category Libraries
 * @package  BrowserMobProxy
 * @author   Stuart Herbert <stuart.herbert@datasift.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0
 * @link     http://www.datasift.com
 */

class BrowserMobProxyBase
{
    /**
     * the URL of the browsermob-proxy server we are using
     * @var string
     */
    protected $url;

    /**
     * The set of feature flags downloaded from browsermob-proxy
     * @var stdClass
     */
    protected $features;

    /**
     * constructor
     *
     * @param string $url the URL where the Selenium server can be found
     */
    public function __construct($url = 'http://localhost:9090')
    {
        $this->url = $url;
        $this->features = new stdClass();

        // download the feature flag list from browsermob-proxy
        $this->getFeatures();

        // switch on enhanced replies if available
        $this->checkForEnhancedReplies();
    }

    /**
     * convert this class for printing to the screen
     * @return string [description]
     */
    public function __toString() {
        return $this->url;
    }

    /**
     * get the URL of the browsermob-proxy server we are talking to
     * @return string URL of the browsermob-proxy server
     */
    public function getURL() {
        return $this->url;
    }

    /**
     * Curl request to browsermob-proxy server.
     *
     * @var string $http_verb  'GET', 'POST', 'DELETE', or 'PUT'
     * @var string $path       the URL to connect to (appended to $this->getUrl())
     * @var mixed  $payload    the parameters to send; an array is sent as POSTFIELDS,
     *                         anything else is sent as raw body
     * @var array  $curl_opts  any additional options to set in curl
     */
    protected function curl(
        $http_verb,
        $path,
        $payload = null,
        $curl_opts = array()
    )
    {
        // determine the URL we are posting to
        if (substr($this->url, -1, 1) !== '/' && substr($path, 0, 1) !== '/')
        {
            $url = $this->url . '/' . $path;
        }
        else
        {
            $url = $this->url . $path;
        }

        // create the curl request
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json;charset=UTF-8',
                'Accept: application/json'
            )
        );

        // handle the different kind of verbs
        switch($http_verb) {
            case 'POST':
                // we are POSTing to the URL
                curl_setopt($curl, CURLOPT_POST, true);

                // what are we posting?
                if ($payload) {
                    if (is_array($payload)) {
                        // posting an array of values
                        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($payload));
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
                    }
                    else if (is_object($payload)){
                        // sending over a JSON payload
                        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
                    }
                    else {
                        // we assume that the caller has done this
                        // for themselves
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
                    }
                }
                break;

            case 'PUT':
                // we are PUT to the URL
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');

                // what are we posting?
                if ($payload) {
                    if (is_array($payload)) {
                        // posting an array of values
                        $fields = http_build_query($payload);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/x-www-form-urlencoded',
                            'Content-Length: ' . strlen($fields)
                        ));
                    }
                    else if (is_object($payload)){
                        // sending over a JSON payload
                        $data = json_encode($payload);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($data)));
                    }
                    else {
                        // we assume that the caller has done this
                        // for themselves
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($payload)));
                    }
                }
                break;

            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        foreach ($curl_opts as $option => $value) {
            curl_setopt($curl, $option, $value);
        }

        // make the curl request
        $raw_results = trim(curl_exec($curl));

        // find out from curl what happened
        $info = curl_getinfo($curl);

        // was there an error?
        if ($error = curl_error($curl)) {
            // yes, there was
            // we throw an exception to explain that the call failed
            $msg = sprintf(
                'Curl error thrown for http %s to %s',
                $http_verb,
                $url
            );
            if ($payload && is_array($payload)) {
                $msg .= sprintf(' with params: %s', json_encode($payload));
            }
            throw new E5xx_BrowserMobProxyCurlException($msg . "\n\n" . $error);
        }
        // we're done with curl for this request
        curl_close($curl);

        // convert the response from webdriver into something we can work with
        $results = json_decode($raw_results);

        // are we working with enhanced replies?
        if (isset($results->success) && $results->success) {
            if (isset($results->data)) {
                return $results->data;
            }
            else {
                return null;
            }
        }
        else if (isset($results->error) && $results->error) {
            // there was an error?
            $e = new E5xx_BrowserMobProxyCurlException(json_encode($results->data));
            $e->data = $results->data;

            throw $e;
        }
        else {
            // no, we do not appear to be
            return $results;
        }

        // all done
        return $results;
    }

    protected function checkForEnhancedReplies()
    {
        if ($this->hasFeature('enhancedReplies') && ! $this->hasFeatureEnabled('enhancedReplies')) {
            $this->enableFeature('enhancedReplies');
        }
    }

    public function getFeatures()
    {
        // does this copy of browsermob-proxy have the /features API at all?
        $response = $this->curl('GET', '/features');
        if ($response == null)
        {
            // no, it does not
            $this->features = (object) array();
        }

        // remember what the result was
        $this->features = $response;

        // all done
        return $this->features;
    }

    public function disableFeature($name)
    {
        // does browsermob-proxy have this feature?
        if (!isset($this->features->$name))
        {
            // no it does not
            throw new E5xx_UnsupportedFeature($name);
        }

        // is the feature currently enabled?
        if (!$this->hasFeatureEnabled($name))
        {
            // no - so we do not need to proceed
            return;
        }

        // disable the feature
        $this->curl(
            'POST',
            '/features/' . $name,
            array($name => 'false')
        );

        // refresh our list of enabled features
        $this->getFeatures();

        // did it work?
        if ($this->hasFeatureEnabled($name)) {
            // no, it did not
            throw new E5xx_FeatureDisableFail($name);
        }
    }

    public function enableFeature($name)
    {
        // does browsermob-proxy have this feature?
        if (!isset($this->features->$name))
        {
            // no, it does not
            throw new E5xx_UnsupportedFeature($name);
        }

        // enable the feature
        $this->curl(
            'POST',
            '/features/' . $name,
            array($name => 'true')
        );

        // refresh our list of enabled features
        $this->getFeatures();

        // did it work?
        if (!$this->hasFeatureEnabled($name)) {
            // no, it did not
            throw new E5xx_FeatureEnableFail($name);
        }

        // all done
    }

    public function hasFeature($name)
    {
        if (!isset($this->features->$name))
        {
            return false;
        }

        return true;
    }

    public function hasFeatureEnabled($name)
    {
        if (!isset($this->features->$name) || ! $this->features->$name)
        {
            return false;
        }

        return true;
    }

    public function requireFeature($name)
    {
        if (!$this->hasFeature($name))
        {
            throw new E5xx_UnsupportedFeature($name);
        }

        if (!$this->hasFeatureEnabled($name))
        {
            $this->enableFeature($name);
        }
    }
}