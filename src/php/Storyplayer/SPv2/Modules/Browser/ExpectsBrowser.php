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

namespace Storyplayer\SPv2\Modules\Browser;

use Prose\Prose;
use Storyplayer\SPv2\Modules\Browser;

/**
 * Test the current contents of the browser
 *
 * @category  Libraries
 * @package   Storyplayer/Modules/Browser
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class ExpectsBrowser extends Prose
{
    protected function initActions()
    {
        $this->initDevice();
    }

    public function doesntHave()
    {
        $action = function($requiredCount, $elements, $elementName, $elementDesc) {

            $log = usingLog()->startAction("$elementDesc '$elementName' must not exist");

            // how many elements actually exist?
            $actualCount = count($elements);

            // this gets a little complicated
            switch ($requiredCount) {
                // this satisfies something like:
                //
                // expectsBrowser()->doesntHave()->anyFieldsWithId('XXX');
                case null:
                case 'any':
                    if ($actualCount === 0) {
                        $log->endAction("0 element(s) found");
                        return;
                    }
                    throw new E5xx_ExpectFailed(__METHOD__, "0 element(s) to exist", "$actualCount element(s) exist");

                case 'several':
                    if ($actualCount < 3 || $actualCount > 9) {
                        $log->endAction($actualCount . " element(s) found");
                        return true;
                    }

                    throw new E5xx_ExpectFailed(__METHOD__, "must not be several element(s)", "several element(s) exist");

                // this satisfies something like:
                //
                // expectsBrowser()->doesntHave()->fiveFieldsWithId('XX');
                default:
                    if ($actualCount != $requiredCount) {
                        $log->endAction($actualCount . " element(s) found");
                        return true;
                    }

                    throw new E5xx_ExpectFailed(__METHOD__, "$requiredCount element(s) must not exist", "$actualCount element(s) exist");
            }
        };

        return new MultiElementAction(
            $action,
            "doesntHave",
            $this->getTopElement()
        );
    }

    public function has()
    {
        $action = function($requiredCount, $elements, $elementName, $elementDesc) {

            $log = usingLog()->startAction("$elementDesc '$elementName' must exist");

            $actualCount = count($elements);
            switch($requiredCount) {
                // this satisfies something like:
                //
                // expectsBrowser()->has()->fieldWithId('XX');
                case null:
                case 'any':
                    if ($actualCount > 0) {
                        $log->endAction($actualCount . " element(s) found");
                        return true;
                    }

                    throw new E5xx_ExpectFailed(__METHOD__, "at least one element to exist", "$actualCount element(s) exist");

                case 'several':
                    if ($actualCount > 2 && $actualCount < 10) {
                        $log->endAction($actualCount . " element(s) found");
                        return true;
                    }

                    throw new E5xx_ExpectFailed(__METHOD__, "several element to exist", "$actualCount element(s) exist");

                // this satisfies something like:
                //
                // expectsBrowser()->has()->oneFieldWithId('XX');
                default:
                    if ($actualCount == $requiredCount) {
                        $log->endAction($actualCount . " element(s) found");
                        return true;
                    }

                    throw new E5xx_ExpectFailed(__METHOD__, "$requiredCount element(s) to exist", "$actualCount element(s) exist");
            }
        };

        return new MultiElementAction(
            $action,
            "has",
            $this->getTopElement()
        );
    }

    public function hasTitle($title)
    {
        // what are we doing?
        $log = usingLog()->startAction("page title must be {$title}");

        // get the browser title
        $browserTitle = Browser::fromBrowser()->getTitle();

        if ($title != $browserTitle) {
            throw new E5xx_ExpectFailed('BrowserExpects::title', $title, $browserTitle);
        }

        // all done
        $log->endAction();
    }

    public function hasTitles($titles)
    {
        // what are we doing?
        $titlesString = implode('; or ', $titles);
        $log = usingLog()->startAction("page title must be one of: {$titlesString}");

        // get the browser title
        $browserTitle = fromBrowser()->getTitle();

        if (!in_array($browserTitle, $titles)) {
            throw new E5xx_ExpectFailed(__METHOD__, $titlesString, $browserTitle);
        }

        // all done
        $log->endAction();
    }

    public function currentWindowSizeIs($width, $height)
    {
        // what are we doing?
        $log = usingLog()->startAction("current browser window dimensions must be '{$width}' x '{$height}' (w x h)");

        // get the dimensions
        $dimensions = fromBrowser()->getCurrentWindowSize();

        // are they right?
        if ($dimensions['width'] != $width || $dimensions['height'] != $height) {
            throw new E5xx_ExpectFailed(__METHOD__, "$width x $height", "{$dimensions['width']} x {$dimensions['height']}");
        }

        // all done
        $log->endAction();
    }

    public function __call($methodName, $methodParams)
    {
        return new SingleElementExpect($this->getTopElement(), $methodName, $methodParams);
    }
}
