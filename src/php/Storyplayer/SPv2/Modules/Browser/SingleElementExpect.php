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
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use Prose\E5xx_ExpectFailed;

/**
 * Helper class for testing elements using convenient, human-like names
 * and terms for elements (such as 'buttonLabelled')
 *
 * @category  Libraries
 * @package   Storyplayer/Browser
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class SingleElementExpect
{
    protected $topElement;
    protected $searchMethodName;
    protected $searchMethodParams;

    /**
     * @param \DataSift\WebDriver\WebDriverElement $topElement
     * @param string $searchMethodName
     * @param array  $searchMethodParams
     */
    public function __construct($topElement, $searchMethodName, $searchMethodParams)
    {
        $this->topElement         = $topElement;
        $this->searchMethodName   = $searchMethodName;
        $this->searchMethodParams = $searchMethodParams;
    }

    public function isBlank()
    {
        $action = function($element, $elementName, $elementDesc) {
            $log = usingLog()->startAction("$elementDesc '$elementName' must be blank");

            // test it
            if (strlen($element->attribute("value")) == 0) {
                $log->endAction();
                return true;
            }

            throw new E5xx_ExpectFailed(__METHOD__, $elementName . ' is blank', $elementName . ' is not blank');
        };

        $wrapper = new SingleElementAction(
            $action,
            "check",
            $this->topElement
        );

        $method = $this->searchMethodName;
        $params = $this->searchMethodParams;

        call_user_func_array([$wrapper, $method], $params);
    }

    public function isNotBlank()
    {
        $action = function($element, $elementName, $elementDesc) {
            $log = usingLog()->startAction("$elementDesc '$elementName' must not be blank");

            // test it
            if (strlen($element->attribute("value")) > 0) {
                $log->endAction();
                return true;
            }

            throw new E5xx_ExpectFailed(__METHOD__, $elementName . ' is not blank', $elementName . ' is blank');
        };

        $wrapper = new SingleElementAction(
            $action,
            "check",
            $this->topElement
        );

        $method = $this->searchMethodName;
        $params = $this->searchMethodParams;

        call_user_func_array([$wrapper, $method], $params);
    }

    public function isChecked()
    {
        $action = function($element, $elementName, $elementDesc) {
            $log = usingLog()->startAction("$elementDesc '$elementName' must be checked");

            // test it
            if ($element->attribute("checked")) {
                $log->endAction();
                return true;
            }

            throw new E5xx_ExpectFailed(__METHOD__, $elementName . ' is checked', $elementName . ' is not checked');
        };

        $wrapper = new SingleElementAction(
            $action,
            "check",
            $this->topElement
        );

        $method = $this->searchMethodName;
        $params = $this->searchMethodParams;

        call_user_func_array([$wrapper, $method], $params);
    }

    public function isNotChecked()
    {
        $action = function($element, $elementName, $elementDesc) {
            $log = usingLog()->startAction("$elementDesc '$elementName' must not be checked");

            // test it
            if (!$element->attribute("checked")) {
                $log->endAction();
                return true;
            }

            throw new E5xx_ExpectFailed(__METHOD__, $elementName . ' is not checked', $elementName . ' is checked');
        };

        $wrapper = new SingleElementAction(
            $action,
            "check",
            $this->topElement
        );

        $method = $this->searchMethodName;
        $params = $this->searchMethodParams;

        call_user_func_array([$wrapper, $method], $params);
    }
}
