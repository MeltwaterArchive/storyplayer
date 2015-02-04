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
 * @package   Storyplayer/ConfigLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\ConfigLib;

/**
 * Base class for all of our built-in configs
 *
 * @category  Libraries
 * @package   Storyplayer/ConfigLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class HardCodedList
{
	/**
	 * our list of configs
	 * @var array<object>
	 */
    private $configs = [];

    /**
     * what config type are we a list of?
     *
     * @var string
     */
    private $configType;

    /**
     * constructor
     *
     * @param string $configType
     *        what type of config are we a list of?
     */
    public function __construct($configType)
    {
    	$this->setConfigType($configType);
    }

    /**
     * returns the classname for the config data that we store
     *
     * @return string
     */
    public function getConfigType()
    {
    	return $this->configType;
    }

    /**
     * what kind of config are we a list of?
     *
     * must be a valid class name
     *
     * @param string $configType
     *        the class name for the data that we are a list of
     */
    public function setConfigType($configType)
    {
    	if (!class_exists($configType)) {
    		throw new E4xx_NoSuchConfigClass($configType);
    	}

    	$this->configType = $configType;
    }

    /**
     * create a new config object, and return it to be modified
     *
     * the returned object has already been added to our list, and does
     * not need to be added manually
     *
     * @param  string $name
     *         the name of this new config entry
     * @return object
     */
    public function newConfig($name)
    {
    	// create our return object
    	$classname = $this->configType;
    	$obj = new $classname();
    	$obj->setName($name);

    	// add this to the list
    	$this->addConfig($obj);

    	return $obj;
    }

    /**
     * add a new config object to the list
     *
     * @param object $config
     *        the config object to add to the list
     */
    public function addConfig($config)
    {
    	// make sure the config is compatible first
    	if (! $config instanceof $this->configType) {
    		throw new E4xx_IncompatibleConfigClass($this->configType, get_class($config));
    	}

    	// if we get here, then we're good to go
    	$name = $config->getName();
        $this->configs[$name] = $config;

        // keep the list sorted
        ksort($this->configs);
    }

    /**
     * return our list of configs
     *
     * @return array<object>
     */
    public function getConfigs()
    {
        return $this->configs;
    }
}
