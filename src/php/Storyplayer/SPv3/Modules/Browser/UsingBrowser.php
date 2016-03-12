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
 * @package   Storyplayer/Modules/Browser
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv3\Modules\Browser;

use Exception;
use Prose\Prose;
use Storyplayer\SPv3\Modules\Exceptions;

/**
 * Do things using the web browser
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class UsingBrowser extends Prose
{
    protected function initActions()
    {
        $this->initDevice();
    }

    // ==================================================================
    //
    // Input actions go here
    //
    // ------------------------------------------------------------------

    /**
     * tick a checkbox or radio button if it has not yet been checked
     *
     * @return \DataSift\Storyplayer\BrowserLib\SingleElementAction
     */
    public function check()
    {
        $action = function($element, $elementName, $elementDesc) {
            $log = usingLog()->startAction("check $elementDesc '$elementName'");

            // does the element need clicking to check it?
            if (!$element->selected()) {
                // click the element to check it
                $element->click();
                $log->endAction();
            }
            else {
                $log->endAction("was already checked");
            }
        };

        return new SingleElementAction(
            $action,
            "check",
            $this->topElement
        );
    }

    /**
     * remove any content from an input box, or untick a checkbox or
     * radio button
     *
     * @return \DataSift\Storyplayer\BrowserLib\SingleElementAction
     */
    public function clear()
    {
        $action = function($element, $elementName, $elementDesc) {
            // what are we doing?
            $log = usingLog()->startAction("clear $elementDesc '$elementName'");

            // clear the element if we can
            $tag = $element->name();
            switch ($tag) {
                case "input":
                case "textarea":
                    $element->clear();
                    break;
            }

            // all done
            $log->endAction();
        };

        return new SingleElementAction(
            $action,
            "clear",
            $this->topElement
        );
    }

    /**
     * Send a 'click' to the selected element
     *
     * @return \DataSift\Storyplayer\BrowserLib\SingleElementAction
     */
    public function click()
    {
        $action = function($element, $elementName, $elementDesc) {
            $log = usingLog()->startAction("click $elementDesc '$elementName'");
            $element->click();
            $log->endAction();
        };

        return new SingleElementAction(
            $action,
            "click",
            $this->topElement
        );
    }

    /**
     * choose an option from a <select> box
     *
     * @param  string $label
     *         the human-readable text of the option to select
     * @return \DataSift\Storyplayer\BrowserLib\SingleElementAction
     */
    public function select($label)
    {
        $action = function ($element, $elementName, $elementDesc) use ($label) {

            // what are we doing?
            $log = usingLog()->startAction("choose option '$label' from $elementDesc '$elementName'");

            // get the option to select
            $option = $element->getElement('xpath', 'option[normalize-space(text()) = "' . $label . '" ]');

            // select it
            $option->click();

            // all done
            $log->endAction();
        };

        return new SingleElementAction(
            $action,
            "select",
            $this->topElement
        );
    }

    /**
     * type text into an input field
     *
     * @param  string $text
     *         the text to type
     * @return \DataSift\Storyplayer\BrowserLib\SingleElementAction
     */
    public function type($text)
    {
        $action = function($element, $elementName, $elementDesc) use ($text) {

            // what are we doing?
            $log = usingLog()->startAction("type '$text' into $elementDesc '$elementName'");

            // type the text
            $element->type($text);

            // all done
            $log->endAction();
        };

        return new SingleElementAction(
            $action,
            "type",
            $this->topElement
        );
    }

    // ==================================================================
    //
    // Navigation actions go here
    //
    // ------------------------------------------------------------------

    public function gotoPage($url)
    {
        // some shorthand to make things easier to read
        $browser = $this->device;

        // relative, or absolute URL?
        if (substr($url, 0, 1) == '/') {
            // only absolute URLs are supported
            throw Exceptions::newActionFailedException(__METHOD__, 'only absolute URLs are supported');
        }

        // parse the URL
        $urlParts = parse_url($url);

        // if we have no host, we cannot continue
        if (isset($urlParts['host'])) {
            // do we have any HTTP AUTH credentials to merge in?
            if (fromBrowser()->hasHttpBasicAuthForHost($urlParts['host'])) {
                $adapter = $this->st->getDeviceAdapter();

                // the adapter *might* embed the authentication details
                // into the URL
                $url = $adapter->applyHttpBasicAuthForHost($urlParts['host'], $url);
            }
        }

        // what are we doing?
        $log = usingLog()->startAction("goto URL: $url");

        // tell the browser to move to the page we want
        $browser->open($url);

        // all done
        $log->endAction();
    }

    public function waitForOverlay($timeout, $id)
    {
        // what are we doing?
        $log = usingLog()->startAction("wait for the overlay with id '{$id}' to appear");

        // check for the overlay
        usingTimer()->waitFor(function() use($id) {
            expectsBrowser()->has()->elementWithId($id);
        }, $timeout);

        // all done
        $log->endAction();
    }

    public function waitForTitle($timeout, $title, $failedTitle = null)
    {
        // what are we doing?
        $log = usingLog()->startAction("check that the the right page has loaded");

        // check the title
        usingTimer()->waitFor(function() use($title, $failedTitle) {
            // have we already failed?
            if ($failedTitle && fromBrowser()->getTitle() == $failedTitle) {
                return false;
            }

            // we have not failed yet
            expectsBrowser()->hasTitle($title);
        }, $timeout);

        // all done
        $log->endAction();
    }

    public function waitForTitles($timeout, $titles)
    {
        // what are we doing?
        $log = usingLog()->startAction("check that the the right page has loaded");

        // check the title
        usingTimer()->waitFor(function() use($titles) {
            expectsBrowser()->hasTitles($titles);
        }, $timeout);

        // all done
        $log->endAction();
    }

    // ==================================================================
    //
    // Window actions go here
    //
    // ------------------------------------------------------------------

    public function resizeCurrentWindow($width, $height)
    {
        // shorthand
        $browser = $this->device;

        // what are we doing?
        $log = usingLog()->startAction("change the current browser window size to be {$width} x {$height} (w x h)");

        // resize the window
        $browser->window()->postSize(array("width" => $width, "height" => $height));

        // all done
        $log->endAction();
    }

    public function switchToWindow($name)
    {
        // shorthand
        $browser = $this->device;

        // what are we doing?
        $log = usingLog()->startAction("switch to browser window called '{$name}'");

        // get the list of available window handles
        $handles = $browser->window_handles();

        // we have to iterate over them, to find the window that we want
        foreach ($handles as $handle) {
            // switch to the window
            $browser->focusWindow($handle);

            // is this the window that we want?
            $title = $browser->title();
            if ($title == $name) {
                // all done
                $log->endAction();
                return;
            }
        }

        // if we get here, then we could not find the window we wanted
        // the browser might be pointing at ANY of the open windows,
        // and it might be pointing at no window at all
        throw Exceptions::newActionFailedException(__METHOD__, "No such window '{$name}'");
    }

    public function closeCurrentWindow()
    {
        // shorthand
        $browser = $this->device;

        // what are we doing?
        $log = usingLog()->startAction("close the current browser window");

        // close the current window
        $browser->deleteWindow();

        // all done
        $log->endAction();
    }

    // ==================================================================
    //
    // IFrame actions go here
    //
    // ------------------------------------------------------------------

    public function switchToIframe($id)
    {
        // shorthand
        $browser = $this->device;

        // what are we doing?
        $log = usingLog()->startAction("switch to working inside the iFrame with the id '{$id}'");

        // switch to the iFrame
        $browser->frame(array('id' => $id));

        // all done
        $log->endAction();
    }

    public function switchToMainFrame()
    {
        // shorthand
        $browser = $this->device;

        // what are we doing?
        $log = usingLog()->startAction("switch to working with the main frame");

        // switch to the iFrame
        $browser->frame(array('id' => null));

        // all done
        $log->endAction();
    }

    // ==================================================================
    //
    // Authentication actions go here
    //
    // ------------------------------------------------------------------

    public function setHttpBasicAuthForHost($hostname, $username, $password)
    {
        // what are we doing?
        $log = usingLog()->startAction("set HTTP basic auth for host '{$hostname}': user: '{$username}'; password: '{$password}'");

        try {
            // get the browser adapter
            $adapter = $this->st->getDeviceAdapter();

            // set the details
            $adapter->setHttpBasicAuthForHost($hostname, $username, $password);
        }
        catch (Exception $e)
        {
            throw Exceptions::newActionFailedException(__METHOD__, "unable to set HTTP basic auth; error is: " . $e->getMessage());
        }

        // all done
        $log->endAction();
    }
}
