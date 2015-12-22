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

use Exception;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

/**
 * Helper class for testing elements using convenient, human-like names
 * and terms for elements (such as 'buttonLabelled')
 *
 * @category  Libraries
 * @package   Storyplayer/Modules/Browser
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class TargettedBrowserExpects
{
    protected $st;
    protected $searchFunction;
    protected $searchTerm;
    protected $element;
    protected $elementType;
    protected $elementDesc;

    /**
     * @param string $elementDesc
     */
    public function __construct(StoryTeller $st, callable $searchFunction, $searchTerm, $elementDesc)
    {
        $this->st             = $st;
        $this->searchFunction = $searchFunction;
        $this->searchTerm     = $searchTerm;
        $this->elementDesc    = $elementDesc;
    }

    public function isBlank()
    {
        // what are we doing?
        $log = usingLog()->startAction("{$this->elementDesc} '{$this->searchTerm}' must be blank");

        // get the element
        $element = $this->getElement();

        // test it
        if (strlen($element->attribute("value")) > 0) {
            throw Exceptions::newExpectFailedException(__METHOD__, $this->searchTerm . ' is blank', $this->searchTerm . ' is not blank');
        }

        // all done
        $log->endAction();
        return true;
    }

    public function isNotBlank()
    {
        // what are we doing?
        $log = usingLog()->startAction("{$this->elementDesc} '{$this->searchTerm}' must not be blank");

        // get the element
        $element = $this->getElement();

        // test it
        if (strlen($element->attribute("value")) > 0) {
            $log->endAction();
            return true;
        }

        throw Exceptions::newExpectFailedException(__METHOD__, $this->searchTerm . ' is not blank', $this->searchTerm . ' is blank');
    }

    public function isChecked()
    {
        // what are we doing?
        $log = usingLog()->startAction("{$this->elementDesc} '{$this->searchTerm}' must be checked");

        // get the element
        $element = $this->getElement();

        // test it
        if ($element->attribute("checked")) {
            $log->endAction();
            return true;
        }

        throw Exceptions::newExpectFailedException(__METHOD__, $this->searchTerm . ' checked', $this->searchTerm . ' not checked');
    }

    public function isNotChecked()
    {
        // what are we doing?
        $log = usingLog()->startAction("{$this->elementDesc} '{$this->searchTerm}' must not be checked");

        // get the element
        $element = $this->getElement();

        // test it
        if ($element->attribute("checked")) {
            throw Exceptions::newExpectFailedException(__METHOD__, $this->searchTerm . ' not checked', $this->searchTerm . ' checked');
        }

        // all done
        $log->endAction();
        return true;
    }

    protected function getElement()
    {
        $callable = $this->searchFunction;

        $log = usingLog()->startAction("Find element on page with label, id or name '{$this->searchTerm}'");
        try {
            $element = $callable();
            $log->endAction();

            return $element;
        }
        catch (Exception $e) {
            throw Exceptions::newExpectFailedException(__METHOD__, $this->searchTerm . ' exists', 'does not exist');
        }
    }
}
