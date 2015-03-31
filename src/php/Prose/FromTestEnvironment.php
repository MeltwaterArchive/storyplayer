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

use DataSift\Stone\DataLib\DataPrinter;
use DataSift\Stone\DataLib\DotNotationConvertor;

/**
 * Get information from the active test environment config
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class FromTestEnvironment extends Prose
{
    public function get($name)
    {
        // what are we doing?
        $log = usingLog()->startAction("get '$name' from the test environment config");

        // what is the full path to this data?
        $fullPath = 'target';
        if (!empty($name)) {
            $fullPath = $fullPath . '.' . $name;
        }

        // get the details
        $config = $this->st->getActiveConfig();

        if (!$config->hasData($fullPath)) {
            $log->endAction("no such setting '{$name}'");
            return null;
        }

        // if we get here, then success \o/
        $value = $config->getData($fullPath);

        // log the settings
        $printer  = new DataPrinter();
        $logValue = $printer->convertToString($value);
        $log->endAction("value is: '{$logValue}'");

        // all done
        return $value;
    }

    public function getName()
    {
        // what are we doing?
        $log = usingLog()->startAction("get current test environment name");

        // get the details
        $config = $this->st->getConfig();
        $value  = $config->getData('target.name');

        // all done
        $log->endAction($value);
        return $value;
    }

    public function getOption($optionName)
    {
        // what are we doing?
        $log = usingLog()->startAction("get option '{$optionName}' from test environment");

        // get the details
        $config = $this->st->getConfig();
        $fullPath = 'target.options.' . $optionName;
        $value = null;
        if ($config->hasData($fullPath)) {
            $value = $config->getData($fullPath);
        }

        // log the settings
        $printer  = new DataPrinter();
        $logValue = $printer->convertToString($value);
        $log->endAction($logValue);

        // all done
        return $value;
    }

    public function getModuleSetting($setting)
    {
        // what are we doing?
        $log = usingLog()->startAction("get '{$setting}' from test environment's module settings");

        // get the details
        $fullPath = 'target.moduleSettings.' . $setting;
        $config  = $this->st->getConfig();

        $value = null;
        if ($config->hasData($fullPath)) {
            $value = $config->getData($fullPath);
        }

        // log the settings
        $printer = new DataPrinter();
        $logValue = $printer->convertToString($value);
        $log->endAction("setting is: '{$logValue}'");

        // all done
        return $value;
    }

    /**
     * I think this is used internally at DataSift.
     *
     * We've standardised on 'getConfig()' as the documented name for this
     * functionality across all three of the config-related modules.
     *
     * @return object
     */
    public function getAllSettings()
    {
        return $this->getConfig();
    }

    public function getConfig()
    {
        // what are we doing?
        $log = usingLog()->startAction("get the test environment config");

        // get the details
        $testEnv = $this->st->getTestEnvironmentConfig();

        // var_dump($testEnv);

        // convert into dot notation
        $convertor = new DotNotationConvertor();
        $return    = $convertor->convertToArray($testEnv->getExpandedData($this->st->getConfig()));

        // all done
        $log->endAction();
        return $return;
    }
}