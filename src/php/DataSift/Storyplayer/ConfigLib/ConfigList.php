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

use DataSift\Stone\ObjectLib\BaseObject;
use DataSift\Stone\ConfigLib\ConfigHelper;

/**
 * tracks a list of available configs of a given type
 *
 * @category  Libraries
 * @package   Storyplayer/ConfigLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class ConfigList
{
    /**
     * what kind of config are we wrapping?
     *
     * this is the name of a class from ConfigLib
     *
     * @var string
     */
    private $configType = null;

    /**
     * where are we looking for these configs?
     *
     * @var string
     */
    private $searchFolder = null;

    /**
     * what configs (including hard-coded defaults) do we know about?
     *
     * @var array<SystemUnderTestConfig>
     */
    private $list = [];

    /**
     * constructor
     */
    public function __construct($configType, $searchFolder)
    {
        $this->setConfigType($configType);
        $this->setSearchFolder($searchFolder);
    }

    /**
     * returns the folder where we look for config files
     *
     * @return string|null
     */
    public function getSearchFolder()
    {
        return $this->searchFolder;
    }

    /**
     * tells us where to look for config files
     *
     * @param string $searchFolder
     *        the folder to search inside
     */
    public function setSearchFolder($searchFolder)
    {
        $this->searchFolder = $searchFolder;
    }

    /**
     * find a list of config files in a folder, and load them
     *
     * @return array
     */
    public function findConfigs()
    {
        // look on disk
        $filenames = $this->findConfigFilenames();

        // let's get them processed
        foreach ($filenames as $filename) {
            $config = $this->newWrappedConfigObject();
            $config->loadConfigFromFile($filename);
            $config->validateConfig();
            $this->list[$config->getName()] = $config;
        }

        // we want our list of configs to be sorted, to make presentation
        // to end-users easier
        ksort($this->list);

        // all done
    }

    /**
     * build a list of the config files in the $searchFolder
     *
     * @return array<string>
     */
    protected function findConfigFilenames()
    {
        // where are we looking?
        $searchFolder = $this->getSearchFolder();

        // do we have somewhere to look?
        if (null === $searchFolder || !is_dir($searchFolder)) {
            return [];
        }

        // build our list
        $helper = new ConfigHelper();
        $filenames = $helper->getListOfConfigFilesIn($searchFolder, 'json');

        // all done
        return $filenames;
    }

    /**
     * what type of content are we a list of?
     *
     * @return string
     */
    public function getConfigType()
    {
        return $this->configType;
    }

    /**
     * tells us what type of content we are going to be a list of
     *
     * @param string $configType
     *        the name of a class from ConfigLib
     */
    public function setConfigType($configType)
    {
        $this->configType = $configType;
        $classname = $this->getWrappedConfigClassname();

        if (!class_exists($classname)) {
            throw new E4xx_NoSuchConfigClass($classname);
        }
    }

    /**
     * returns a classname you can use for creating objects
     *
     * @return string
     */
    public function getWrappedConfigClassname()
    {
        return $this->configType;
    }

    /**
     * returns a object of the correct type for the content we are listing
     *
     * @return object
     */
    public function newWrappedConfigObject()
    {
        $classname = $this->getWrappedConfigClassname();
        $obj = new $classname;

        return $obj;
    }

    /**
     * inject a config into our list
     *
     * useful for adding in hard-coded config options
     *
     * @param string $name
     *        the name of the config to inject
     * @param object $config
     *        the config to inject into our list
     */
    public function addEntry($name, $config)
    {
        // make sure $config is the right type
        $classname = $this->getWrappedConfigClassname();
        if (!$config instanceof $classname) {
            throw new E4xx_IncompatibleConfigClass($classname, get_class($config));
        }

        // if we get here, all is good
        $this->list[$name] = $config;

        // keep the configs sorted
        ksort($this->list);
    }

    /**
     * retrieve a single config entry
     *
     * @param  string $name
     *         the name of the config to retrieve
     * @return array|object
     */
    public function getEntry($name)
    {
        if (!isset($this->list[$name])) {
            throw new E4xx_NoSuchConfigEntry($name);
        }

        return $this->list[$name];
    }

    /**
     * do we have a config entry called $name?
     *
     * @param  string $name
     *         the name of the config to check for
     * @return boolean
     */
    public function hasEntry($name)
    {
        if (!isset($this->list[$name])) {
            return false;
        }

        return true;
    }

    /**
     * returns our list of all known configs
     *
     * @return array
     */
    public function getEntries()
    {
        return $this->list;
    }

    /**
     * returns the names of all of the entries in our list
     *
     * @return array<string>
     */
    public function getEntryNames()
    {
        return array_keys($this->list);
    }

    /**
     * add config entries from a hard-coded list
     *
     * @param HardCodedList $hardCodedDefaults
     *        the entries to add
     */
    public function addHardCodedList(HardCodedList $hardCodedDefaults)
    {
        $list = $hardCodedDefaults->getConfigs();

        foreach ($list as $name => $config)
        {
            $this->addEntry($name, $config);
        }
    }
}
