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

/**
 * represents config loaded from a single file
 *
 * @category  Libraries
 * @package   Storyplayer/ConfigLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class WrappedConfig
{
    /**
     * used when we do not know the name of the config we are wrapping
     */
    const NO_NAME = "UNKNOWN";

    const ROOT_IS_OBJECT = false;
    const ROOT_IS_ARRAY = true;

    /**
     * the config settings that this object wraps
     * @var BaseObject|array
     */
    private $config;

    /**
     * remember where we were loaded from
     * @var string|null
     */
    private $filename = null;

    /**
     * the name assigned to this config container
     * @var string|null
     */
    private $name = null;

    /**
     * constructor
     */
    public function __construct($isArray = false)
    {
        $this->setName("UNKNOWN");
        if (!$isArray) {
            $this->setConfig(new BaseObject);
        }
        else {
            $this->setConfig([]);
        }
    }

    /**
     * get the config that we wrap
     *
     * @return array|object
     */
    public function &getConfig()
    {
        return $this->config;
    }

    /**
     * store the config that we wrap
     *
     * @param array|object $config
     *        the config to store
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * load a config file from disk, and store the config in $this
     *
     * @param  string $pathToFile
     *         the filename to load from
     * @return void
     */
    public function loadConfigFromFile($pathToFile)
    {
        if (!file_exists($pathToFile)) {
            throw new E4xx_ConfigFileNotFound($pathToFile);
        }

        $raw = @file_get_contents($pathToFile);
        if (false === $raw) {
            throw new E4xx_ConfigFileNotFound($pathToFile);
        }

        // special case - file is empty
        if (strlen(rtrim($raw)) === 0) {
            // nothing to see, move along :)
            $this->setConfig(new BaseObject);
            $this->setName(basename($pathToFile, '.json'));
            return;
        }

        // if we get here, we expect the file to contain valid JSON
        $json = @json_decode($raw);
        if (null === $json) {
            throw new E4xx_ConfigFileContainsInvalidJson($pathToFile);
        }

        // convert from stdClass to our useful BaseObject
        $config = new BaseObject;
        $config->mergeFrom($json);

        // store the config
        $this->setConfig($config);
        $this->setName(basename($pathToFile, '.json'));
        $this->setFilename($pathToFile);

        // all done
    }

    /**
     * save the config to disk, as a JSON file
     *
     * @return void
     */
    public function saveConfig()
    {
        // do we have a filename?
        $filename = $this->getFilename();
        if ($filename === null) {
            throw new E4xx_ConfigNeedsAFilename($this->getName());
        }

        // make sure that the parent folder exists
        $this->makeConfigDir(dirname($filename));

        // let's get this saved
        $data = json_encode($this->getConfig());
        if (!file_put_contents($filename, $data)) {
            throw new E4xx_ConfigCannotBeSaved($name, $filename);
        }
    }

    /**
     * @return void
     */
    protected function makeConfigDir($configDir)
    {
        if (file_exists($configDir)) {
            // nothing to do
            return;
        }

        // can we make the folder?
        //
        // if this fails, we do not know why
        $success = mkdir($configDir, 0700, true);
        if (!$success)
        {
            // cannot create it - bail out now
            throw new E4xx_ConfigPathCannotBeCreated($configDir);
        }
    }


    /**
     * returns the name assigned to this config
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * assigns a name to this config
     *
     * @param string $name
     *        the name to assign
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * returns the filename we loaded this config from
     *
     * @return string|null
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * sets the filename that we loaded this config from
     *
     * @param string $filename
     *        the filename to store
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    // ==================================================================
    //
    // dot.notation.support
    //
    // ------------------------------------------------------------------

    /**
     * expand any variables in $this
     *
     * @param  array|object|null $baseConfig
     *         the config to use for expanding variables (optional)
     * @return array|object
     *         a copy of the config stored in $this, with any Twig
     *         variables expanded
     */
    public function getExpandedConfig($baseConfig = null)
    {
        return $this->config->getExpandedData($baseConfig);
    }

    /**
     * retrieve data using a dot.notation.path
     *
     * NOTE that you should treat any data returned from here as READ-ONLY
     *
     * @param  string $path
     *         the dot.notation.path to use to navigate
     *
     * @return mixed
     */
    public function getData($path)
    {
        return $this->config->getData($path);
    }

    /**
     * retrieve data from a dot.notation.path
     *
     * throws an exception if the path does not point to an array
     *
     * @param string $path
     *        the dot.notation.path to the data to return
     * @return array
     */
    public function getArray($path)
    {
        return $this->config->getArray($path);
    }

    /**
     * retrieve data from a dot.notation.path
     *
     * throws an exception if the path does not point to an object
     *
     * @param string $path
     *        the dot.notation.path to the data to return
     * @return object
     */
    public function getObject($path)
    {
        return $this->config->getObject($path);
    }

    /**
     * check for existence of data using a dot.notation.path
     *
     * @param  string $path
     *         the dot.notation.path to use to navigate
     *
     * @return boolean
     */
    public function hasData($path)
    {
        return $this->config->hasData($path);
    }

    /**
     * merge data into this config
     *
     * @param  string $path
     *         path.to.merge.to
     * @param  mixed $dataToMerge
     *         the data to merge at $path
     * @return void
     */
    public function mergeData($path, $dataToMerge)
    {
        return $this->config->mergeData($path, $dataToMerge);
    }

    /**
     * assigns data to a specific path
     *
     * @param string $path
     *        the path to assign to
     * @param mixed $data
     *        the data to assign
     *
     * @return void
     */
    public function setData($path, $data)
    {
        // special case
        if ($path == "") {
            $this->config = $data;
            return;
        }

        // general case
        $this->config->setData($path, $data);
    }

    /**
     * support for arrow->notation support on wrapped configs
     *
     * NOTE: objects returned by arrow->notation are READ-WRITE
     *
     * @param  string $path
     *         the variable to be retrieved
     * @return mixed
     *         the fake attribute
     */
    public function __get($path)
    {
        return $this->config->getPath($path);
    }

    /**
     * support for arrow->notation on wrapped configs
     *
     * @param string $path
     *        the name of the attribute to save
     * @param mixed $data
     *        the data to save
     * @return void
     */
    public function __set($path, $data)
    {
        return $this->setData($path, $data);
    }

    /**
     * remove data using a dot.notation.path
     *
     * @param  string $path
     *         the dot.notation.path to use to navigate
     *
     * @return void
     */
    public function unsetData($path)
    {
        return $this->config->unsetData($path);
    }
}
