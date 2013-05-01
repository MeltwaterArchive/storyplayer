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
 * @package   BrowserMobProxy
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2004-present Facebook
 * @copyright 2012-present MediaSift Ltd
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://www.datasift.com
 */

namespace DataSift\WebDriver;

/**
 * Base class for all classes that interact with the WebDriver
 *
 * @category Libraries
 * @package  WebDriver
 * @license  http://www.apache.org/licenses/LICENSE-2.0
 * @link     http://www.datasift.com
 * @link     http://facebook.com
 */
abstract class WebDriverBase
{
    /**
     * Returns the name of the exception class to throw
     * @param  int    $status_code the status code returned from webdriver
     * @return string              the name of the exception class to throw, or null if no error occurred
     */
    public function returnExceptionToThrow($status_code)
    {
        static $map = array (
            1  => 'E4xx_IndexOutOfBoundsWebDriverError',
            2  => 'E4xx_NoCollectionWebDriverError',
            3  => 'E4xx_NoStringWebDriverError',
            4  => 'E4xx_NoStringLengthWebDriverError',
            5  => 'E4xx_NoStringWrapperWebDriverError',
            6  => 'E4xx_NoSuchDriverWebDriverError',
            7  => 'E4xx_NoSuchElementWebDriverError',
            8  => 'E4xx_NoSuchFrameWebDriverError',
            9  => 'E5xx_UnknownCommandWebDriverError',
            10 => 'E4xx_ObsoleteElementWebDriverError',
            11 => 'E4xx_ElementNotDisplayedWebDriverError',
            12 => 'E5xx_InvalidElementStateWebDriverError',
            13 => 'E5xx_UnhandledWebDriverError',
            14 => 'E4xx_ExpectedWebDriverError',
            15 => 'E4xx_ElementNotSelectableWebDriverError',
            16 => 'E4xx_NoSuchDocumentWebDriverError',
            17 => 'E5xx_UnexpectedJavascriptWebDriverError',
            18 => 'E4xx_NoScriptResultWebDriverError',
            19 => 'E4xx_XPathLookupWebDriverError',
            20 => 'E4xx_NoSuchCollectionWebDriverError',
            21 => 'E4xx_TimeOutWebDriverError',
            22 => 'E5xx_NullPointerWebDriverError',
            23 => 'E4xx_NoSuchWindowWebDriverError',
            24 => 'E4xx_InvalidCookieDomainWebDriverError',
            25 => 'E4xx_UnableToSetCookieWebDriverError',
            26 => 'E4xx_UnexpectedAlertOpenWebDriverError',
            27 => 'E4xx_NoAlertOpenWebDriverError',
            28 => 'E4xx_ScriptTimeoutWebDriverError',
            29 => 'E4xx_InvalidElementCoordinatesWebDriverError',
            30 => 'E4xx_IMENotAvailableWebDriverError',
            31 => 'E5xx_IMEEngineActivationFailedWebDriverError',
            32 => 'E4xx_InvalidSelectorWebDriverError',
            33 => 'E5xx_SessionNotCreatedWebDriverError',
            34 => 'E4xx_MoveTargetOutOfBoundsWebDriverError',
        );

        // did an error occur?
        if ($status_code == 0) {
            return null;
        }

        // is this a known problem?
        if (isset($map[$status_code])) {
            return __NAMESPACE__ . '\\' . $map[$status_code];
        }

        // we have an unknown exception
        return __NAMESPACE__ . '\\UnknownWebDriverError';
    }

    /**
     * A list of the methods that the child class exposes to the user
     * @return array
     */
    abstract protected function getMethods();

    /**
     * the URL of the webdriver server we are using
     * @var string
     */
    protected $url;

    /**
     * constructor
     *
     * @param string $url the URL where the Selenium server can be found
     */
    public function __construct($url = 'http://localhost:4444/wd/hub')
    {
        $this->url = $url;
    }

    /**
     * convert this class for printing to the screen
     * @return string [description]
     */
    public function __toString() {
        return $this->url;
    }

    /**
     * get the URL of the Selenium webdriver server we are talking to
     * @return string URL of the Selenium webdriver server
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Curl request to webdriver server.
     *
     * $http_method  'GET', 'POST', or 'DELETE'
     * $command      If not defined in methods() this function will throw.
     * $params       If an array(), they will be posted as JSON parameters
     *               If a number or string, "/$params" is appended to url
     * $extra_opts   key=>value pairs of curl options to pass to curl_setopt()
     */
    protected function curl(
        $http_method,
        $command,
        $params = null,
        $extra_opts = array()
    )
    {
        // catch problems with the definition of allowed methods in
        // child classes
        if ($params && is_array($params) && $http_method !== 'POST') {
            throw new E5xx_BadMethodCallWebDriverError(sprintf(
                'The http method called for %s is %s but it has to be POST' .
                ' if you want to pass the JSON params %s',
                $command,
                $http_method,
                json_encode($params)));
        }

        // determine the URL we are posting to
        $url = sprintf('%s%s', $this->url, $command);
        if ($http_method == 'GET' && $params && (is_int($params) || is_string($params))) {
            $url .= '/' . $params;
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
        if ($http_method === 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);

            if ($params) {
                if (is_array($params)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
                }
                else {
                    // assume they've already been encoded
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                }
            }
        }
        else if ($http_method == 'DELETE') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }
        foreach ($extra_opts as $option => $value) {
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
                $http_method,
                $url
            );
            if ($params && is_array($params)) {
                $msg .= sprintf(' with params: %s', json_encode($params));
            }
            throw new E5xx_WebDriverCurlException($msg . "\n\n" . $error);
        }
        // we're done with curl for this request
        curl_close($curl);

        // convert the response from webdriver into something we can work with
        $results = json_decode($raw_results, true);

        // did we get a value back from webdriver?
        $value = null;
        if (is_array($results) && array_key_exists('value', $results)) {
            $value = $results['value'];
        }

        // did we get a message back from webdriver?
        $message = null;
        if (is_array($value) && array_key_exists('message', $value)) {
            $message = $value['message'];
        }

        // did webdriver send us back an error?
        if ($results['status'] != 0) {
            // yes it did ... throw the appropriate exception from here
            $className = $this->returnExceptionToThrow($results['status']);
            throw new $className($results['status'], $message, $results);
        }

        // if we get here, return the results back to the caller
        return array('value' => $value, 'info' => $info);
    }

    /**
     * The magic that converts a PHP method call into the HTTP request
     * to webdriver
     *
     * @param  string $name      the name of the PHP method
     * @param  array  $arguments the arguments passed to the PHP method
     * @return array             the result returned from webdriver
     */
    public function __call($name, $arguments)
    {
        // make sure the argument count is legit
        if (count($arguments) > 1) {
            throw new E5xx_BadMethodCallWebDriverError(
                'Commands should have at most only one parameter,' .
                ' which should be the JSON Parameter object'
            );
        }

        // the start of the PHP method call tells us which HTTP verb
        // we are going to use to talk to webdriver
        if (preg_match('/^(get|post|delete)/', $name, $matches)) {
            $http_verb = strtoupper($matches[0]);

            $methods = $this->getMethods();
            if (!in_array($http_verb, $methods[$webdriver_command])) {
                throw new E5xx_BadMethodCallWebDriverError(sprintf(
                    '%s is not an available http method for the command %s.',
                    $http_verb,
                    $webdriver_command
                ));
            }
        } else {
            // special case - methods that look odd when prefixed with
            // 'get' or 'post' or 'delete'. we use the methods() map
            // to look these up
            $webdriver_command = $name;
            $http_verb = $this->getHttpVerb($webdriver_command);
        }

        // make the HTTP call using our curl wrapper
        echo "$http_verb /$webdriver_command\n";
        $results = $this->curl(
            $http_verb,
            '/' . $webdriver_command,
            array_shift($arguments)
        );

        return $results['value'];
    }

    /**
     * determine the HTTP verb to use for a given webdriver command
     *
     * @param  string $webdriver_command the webdriver command to use
     * @return string                    the HTTP verb to use
     */
    private function getHttpVerb($webdriver_command)
    {
        $methods = $this->getMethods();

        if (!isset($methods[$webdriver_command])) {
            throw new E5xx_BadMethodCallWebDriverError(sprintf(
                '%s is not a valid webdriver command.',
                $webdriver_command
            ));
        }

        // the first element in the array is the default HTTP verb to use
        return $methods[$webdriver_command];
    }
}