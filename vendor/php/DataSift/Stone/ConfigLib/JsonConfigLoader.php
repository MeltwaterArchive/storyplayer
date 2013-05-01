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
 * @package   Stone/ConfigLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */

namespace DataSift\Stone\ConfigLib;

use stdClass;

/**
 * A simple library to load a JSON-encoded config file from disk
 *
 * @category  Libraries
 * @package   Stone/ConfigLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class JsonConfigLoader extends BaseConfigLoader implements ConfigLoader
{
    /**
     * constructor
     *
     * @param string $appName
     *        the name of the app we are loading configs for
     *
     *        this is used as the default name of the config file that
     *        we will attempt to load
     *
     * @param string $topDir
     *        the location where the app is installed, or the folder
     *        where the app's github checkout can be found
     */
    public function __construct($appName, $topDir)
    {
        parent::__construct($appName, $topDir, 'json');
    }

    /**
     * convert config data into a JSON string
     *
     * @param  stdClass $config
     *         the config data to convert
     * @return string
     *         the JSON-encoding of the config data
     */
    public function encodeConfig(stdClass $config)
    {
    	return json_encode($config);
    }

    /**
     * convert a JSON string into config data
     *
     * @param  string $rawConfig
     *         the JSON string to convert into config data
     * @return stdClass
     *         the decoded config data
     */
    public function decodeLoadedFile($rawConfig)
    {
        return json_decode($rawConfig);
    }
}