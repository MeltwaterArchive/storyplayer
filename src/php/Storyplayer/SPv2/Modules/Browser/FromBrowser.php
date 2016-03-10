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
 * @package   Storyplayer/Browser
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv2\Modules\Browser;

use Exception;
use Prose\Prose;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\PlayerLib\Action_LogItem;
use Storyplayer\SPv2\Modules\Exceptions;

/**
 * Get information from the browser
 *
 * @category  Libraries
 * @package   Storyplayer/Browser
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class FromBrowser extends Prose
{
    protected function initActions()
    {
        $this->initDevice();
    }

    // ==================================================================
    //
    // Data extractors go here
    //
    // ------------------------------------------------------------------

    public function getTableContents($xpath)
    {
        // what are we doing?
        $log = usingLog()->startAction("get HTML table using xpath");

        // can we find the table?
        try {
            $tableElement = fromBrowser()->get()->elementByXpath($xpath);
        }
        catch (Exception $e) {
            // no such table
            $log->endAction("no matching table");

            throw Exceptions::newActionFailedException(__METHOD__);
        }

        // at this point, it looks like we'll have something to return
        $return = [];

        // extract the headings
        $headings = [];
        $thElements = $tableElement->getElements('xpath', 'descendant::thead/tr/th');
        foreach ($thElements as $thElement) {
            $headings[] = $thElement->text();
        }

        // extract the contents
        $row = 0;
        $column = 0;
        $trElements = $tableElement->getElements('xpath', 'descendant::tbody/tr');
        foreach ($trElements as $trElement) {
            $column = 0;
            $tdElements = $trElement->getElements('xpath', "descendant::td");

            foreach ($tdElements as $tdElement) {
                if (isset($headings[$column])) {
                    $return[$row][$headings[$column]] = $tdElement->text();
                }
                else {
                    $return[$row][] = $tdElement->text();
                }
                $column++;
            }

            $row++;
        }

        // all done
        $log->endAction("found table with $column columns and $row rows");
        return $return;
    }

    // ==================================================================
    //
    // Tests for elements go here
    //
    // ------------------------------------------------------------------

    public function has()
    {
        $action = function($element, $elementName, $elementDesc) {

            $log = usingLog()->startAction("check the current page for $elementDesc '$elementName'");
            if (is_object($element)) {
                $log->endAction('found it');
                return true;
            }

            $log->endAction('could not find it');
            return false;
        };

        return new SingleElementAction(
            $action,
            "has",
            $this->getTopElement()
        );
    }

    public function get()
    {
        $action = function($element, $elementName, $elementDesc) {

            $log = usingLog()->startAction("retrieve the $elementDesc '$elementName'");
            $log->endAction();
            return $element;
        };

        return new SingleElementAction(
            $action,
            "get",
            $this->getTopElement()
        );
    }

    public function getName()
    {
        $action = function($element, $elementName, $elementDesc) {

            $log = usingLog()->startAction("retrieve the name of the $elementDesc '$elementName'");
            $log->endAction('name is: ' . $element->attribute('name'));
            return $element->attribute('name');
        };

        return new SingleElementAction(
            $action,
            "getName",
            $this->getTopElement()
        );
    }

    public function getNames()
    {
        $action = function($elements, $elementName, $elementDesc) {

            $log = usingLog()->startAction("retrieve the names of the $elementDesc '$elementName'");
            if (!is_array($elements)) {
                $log->endAction('1 element found');
                return $elements->attribute('name');
            }

            $return = array();
            foreach ($elements as $element) {
                $return[] = $element->attribute('name');
            }

            $log->endAction(count($return) . ' element(s) found');
            return $return;
        };

        return new SingleElementAction(
            $action,
            "getNames",
            $this->getTopElement()
        );
    }

    public function getOptions()
    {
        $action = function($element, $elementName, $elementDesc) {

            $log = usingLog()->startAction("retrieve the options of them $elementDesc '$elementName'");
            // get the elements
            $optionElements = $element->getElements('xpath', "descendant::option");

            // extract their values
            $return = array();
            foreach ($optionElements as $optionElement) {
                $return[] = $optionElement->text();
            }

            // all done
            $log->endAction(count($return) . ' option(s) found');
            return $return;
        };

        return new SingleElementAction(
            $action,
            'getOptions',
            $this->getTopElement()
        );
    }

    public function getTag()
    {
        $action = function($element, $elementName, $elementDesc) {
            $log = usingLog()->startAction("retrieve the tagname of the $elementDesc '$elementName'");
            $log->endAction("tag is: " . $element->name());
            return $element->name();
        };

        return new SingleElementAction(
            $action,
            "getTag",
            $this->getTopElement()
        );
    }

    public function getText()
    {
        $action = function($element, $elementName, $elementDesc) {
            $log = usingLog()->startAction("retrieve the text of the $elementDesc '$elementName'");
            $log->endAction("text is: " . $element->text());
            return $element->text();
        };

        return new SingleElementAction(
            $action,
            "getText",
            $this->getTopElement()
        );
    }

    public function getValue()
    {
        $action = function($element, $elementName, $elementDesc) {
            $log = usingLog()->startAction("retrieve the value of the $elementDesc '$elementName'");

            // is this a select box?
            switch($element->name()) {
                case 'select':
                    // get the option that is selected
                    try {
                        $option = $element->getElement('xpath', 'option[@selected]');
                        $log->endAction("value is: " . $option->text());
                        return $option->text();
                    }
                    catch (Exception $e) {
                        // return the top option from the list
                        $option = $element->getElement('xpath', 'option[1]');
                        $log->endAction("value is: " . $option->text());
                        return $option->text();
                    }

                case 'input':
                    $log->endAction("value is: " . $element->attribute("value"));
                    return $element->attribute("value");

                default:
                    $log->endAction("value is: " . $element->text());
                    return $element->text();
            }
        };

        return new SingleElementAction(
            $action,
            "getValue",
            $this->getTopElement()
        );
    }

    // ==================================================================
    //
    // Retrievers of navigation metadata
    //
    // ------------------------------------------------------------------

    public function getUrl()
    {
        // shorthand
        $browser = $this->device;

        // what are we doing?
        $log = usingLog()->startAction("retrieve the current URL from the browser");

        // get the URL
        $url = $browser->url();

        // all done
        $log->endAction($url);

        return $url;
    }

    // ==================================================================
    //
    // Authentication actions go here
    //
    // ------------------------------------------------------------------

    public function getHttpBasicAuthForHost($hostname)
    {
        // this method deliberately has no logging, because it is called
        // every single time that we want the browser to go to a new URL
        //
        // nothing else should really use this

        try {
            // get the browser adapter
            $adapter = $this->st->getDeviceAdapter();

            // get the details
            $credentials = $adapter->getHttpBasicAuthForHost($hostname);

            // all done
            return $credentials;
        }
        catch (Exception $e)
        {
            return null;
        }
    }

    public function hasHttpBasicAuthForHost($hostname)
    {
        // this method deliberately has no logging, because it is called
        // every single time that we want the browser to go to a new URL
        //
        // nothing else should really use this

        try {
            // get the browser adapter
            $adapter = $this->st->getDeviceAdapter();

            // get the details
            return $adapter->hasHttpBasicAuthForHost($hostname);
        }
        catch (Exception $e)
        {
            return false;
        }
    }

    // ==================================================================
    //
    // Retrievers of page metadata
    //
    // ------------------------------------------------------------------

    public function getTitle()
    {
        // some shorthand to make things easier to read
        $browser = $this->device;

        $log = usingLog()->startAction("retrieve the current page title");
        $log->endAction("title is: " . $browser->title());

        return $browser->title();
    }

    // ==================================================================
    //
    // Retrievers of browser metadata
    //
    // ------------------------------------------------------------------

    public function getCurrentWindowSize()
    {
        // shorthand
        $browser = $this->device;

        // what are we doing?
        $log = usingLog()->startAction("retrieve the current browser window's dimensions");

        // get the dimensions
        $dimensions = $browser->window()->getSize();

        // all done
        $log->endAction("width: '{$dimensions['width']}'; height: '{$dimensions['height']}'");
        return array('width' => $dimensions['width'], 'height' => $dimensions['height']);
    }

    // ==================================================================
    //
    // Retrievers of browser metadata
    //
    // ------------------------------------------------------------------

    public function takeScreenshot($targetFilename = null)
    {
        // where are we saving the screenshot to?
        if ($targetFilename === null) {
            $targetFilename = date('Y-m-d-h-i-s') . '.png';
        }

        // what are we doing?
        $log = Log::usingLog()->startAction("take a screenshot and save it into file '{$targetFilename}'");

        // get the screenshot
        $screenshot = $this->device->screenshot();
        $screenshot = base64_decode($screenshot);

        // save it
        file_put_contents($targetFilename, $screenshot);

        // all done
        $log->endAction();
    }
}
