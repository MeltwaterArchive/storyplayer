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
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Prose;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\PlayerLib\Story_Checkpoint;
use DataSift\Stone\DataLib\DataPrinter;
use DataSift\Stone\TextLib\TextHelper;

/**
 * Base class used for all assertions
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class AssertionsBase extends Prose
{
    protected $comparitor = null;

    public function __construct(StoryTeller $st, $comparitor)
    {
        $this->comparitor = $comparitor;
        parent::__construct($st);
    }

    public function __call($methodName, $params)
    {
        // what are we doing?
        //
        // let's try and make it a bit more useful to the reader
        $msg = $this->getStartLogMessage($methodName, $params);
        $log = usingLog()->startAction($msg);
        $actual4Log = $this->getActualDataForLog();
        usingLog()->writeToLog("checking data: " . $actual4Log);

        // is the user trying to call a method that exists in our comparitor?
        if (!method_exists($this->comparitor, $methodName)) {
            $log->endAction("unsupported comparison '{$methodName}'");
            throw new E5xx_NotImplemented(get_class($this) . '::' . $methodName);
        }

        // if we get here, then there's a comparitor we can call
        $result = call_user_func_array(array($this->comparitor, $methodName), $params);

        // was the comparison successful?
        if ($result->hasPassed()) {
            $log->endAction("assertion passed!");
            return true;
        }

        // if we get here, then the comparison failed
        usingLog()->writeToLog("expected outcome: " . $result->getExpected());
        usingLog()->writeToLog("actual   outcome: " . $result->getActual());
        $log->endAction("assertion failed!");
        throw new E5xx_ExpectFailed(__CLASS__ . "::${methodName}", $result->getExpected(), $result->getActual());
    }

    protected function getStartLogMessage($methodName, $params)
    {
        $className = preg_replace('/^.*[\\\\_]([A-Za-z0-9]+)$/', "$1", get_class($this));
        $words = TextHelper::convertCamelCaseToWords($className);
        if (isset($words[1])) {
            $msg = "assert " . strtolower($words[1]) . ' ' . $methodName;
        }
        else {
            $msg = "check data using $className::$methodName";
        }

        foreach ($params as $expected) {
            if (is_string($expected)) {
                $msg .= " '" . $expected . "'";
            }
            else {
                $printer = new DataPrinter;
                $msg .= ' ' . $printer->convertToStringWithTypeInformation($expected);
            }
        }

        // all done
        return $msg;
    }

    protected function getActualDataForLog()
    {
        // special case - our checkpoint object is a 'fake' object
        $actual = $this->comparitor->getValue();

        if ($actual instanceof Story_Checkpoint) {
            $printer = new DataPrinter;
            $actualLogMsg = $printer->convertToStringWithTypeInformation($actual->getData());
        }
        else {
            $actualLogMsg = $this->comparitor->getValueForLogMessage();
        }

        return $actualLogMsg;
    }

    public function getComparator()
    {
        return $this->comparitor;
    }
}
