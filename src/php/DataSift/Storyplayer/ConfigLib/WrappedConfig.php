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
class WrappedConfig extends BaseObject
{
    /**
     * used when we do not know the name of the config we are wrapping
     */
    const NO_NAME = "UNKNOWN";

    const ROOT_IS_OBJECT = false;
    const ROOT_IS_ARRAY = true;

    /**
     * the config settings that this object wraps
     * @var BaseObject
     */
    private $config;

    /**
     * remember where we were loaded from
     * @var string
     */
    private $filename = null;

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
        // we're going to start walking from here
        $config = $this->config;

        // we need to track this for error reporting
        $pathSoFar = "";

        // special case - we treat objects and arrays differently to other
        // data types
        if (!is_object($dataToMerge) && !is_array($dataToMerge)) {
            // we just need to set the data instead
            $this->setData($path, $dataToMerge);
            return;
        }

        // walk down the path
        $parts = explode(".", $path);

        // get to where we need to be
        $leaf =& $this->createPath(implode(".", $parts));

        // if we get here, then we know where we are adding the new
        // data
        if (is_object($leaf)) {
            $leaf->mergeFrom($dataToMerge);
        }
        else if (is_array($leaf)) {
            $leaf = array_merge($leaf, $dataToMerge);
        }
        else {
            throw new E4xx_ConfigPathCannotBeExtended($path, implode(".", $parts), gettype($leaf));
        }

        // all done
    }

    /**
     * expand any variables in $this
     *
     * @param  DataSift\Storyplayer\Injectables $injectables
     *         our dependency injection container
     * @param  array|object $baseConfig
     *         the config to use for expanding variables (optional)
     * @return array|object
     *         a copy of the config stored in $this, with any Twig
     *         variables expanded
     */
    public function getExpandedConfig($injectables, $baseConfig = null)
    {
        // we're going to use Twig to expand any parameters in our
        // config
        //
        // this seems horribly inefficient, but it does work reliably
        //
        // unfortunately, we cannot build any sort of cache, because we
        // have absolutely no way of knowing if $this->config has changed
        // at all

        // Twig is a template engine. it needs a text string to operate on
        $configString = json_encode($this->config);

        // Twig needs an array of data to expand variables from
        if ($baseConfig === null) {
            $baseConfig = $this->config;
        }
        $varData = json_decode(json_encode($baseConfig), true);

        // use Twig to expand any config variables
        $expandedConfig = json_decode($injectables->templateEngine->render(
            $configString, $varData
        ));

        // make sure we have our handy BaseObject, because it does nice
        // things like throw exceptions when someone tries to access an
        // attribute that does not exist
        if (is_object($expandedConfig)) {
            $tmp = new BaseObject();
            $tmp->mergeFrom($expandedConfig);
            $expandedConfig = $tmp;
        }

        // all done
        return $expandedConfig;
    }

    /**
     * assigns data to a specific path
     *
     * @param string $path
     *        the path to assign to
     * @param mixed $data
     *        the data to assign
     */
    public function setData($path, $data)
    {
        // walk down the path
        $parts = explode(".", $path);
        $lastPart = null;
        $lastPart = end($parts);
        $parts = array_slice($parts, 0, count($parts) - 1);

        // create the path
        $leaf =& $this->createPath(implode(".", $parts));

        // if we get here, then we know where we are adding the new
        // data
        if (is_array($leaf)) {
            $leaf[$lastPart] = $data;
        }
        else if (is_object($leaf)) {
            $leaf->$lastPart = $data;
        }
        else {
            // we cannot add to this
            throw new E4xx_ConfigPathCannotBeExtended($path, implode(".", $parts), gettype($leaf));
        }

        // all done
    }

    /**
     * extend the config (if required) to ensure that a dot.notation.path
     * exists
     *
     * @param  string $pathToCreate
     *         the dot.notation.path.to.create
     * @return array|object
     *         whatever is at the end of the path
     */
    protected function &createPath($pathToCreate)
    {
        // this is where we walk from
        $config = $this->getConfig();

        // we need to track this for error reporting
        $pathSoFar = "";

        // split things up
        $parts = explode(".", $pathToCreate);

        // walk down the path
        foreach ($parts as $part) {
            // what are we looking at?
            if (is_object($config)) {
                // are we extending the config tree?
                if (!isset($config->$part)) {
                    // yes we are
                    $config->$part = new BaseObject;
                }

                if (is_array($config->$part)) {
                    $config = &$config->$part;
                }
                else {
                    $config = $config->$part;
                }
            }
            else if (is_array($config)) {
                // we are extending the config tree?
                if (!isset($config[$part])) {
                    // yes we are
                    //
                    // assume that we want an object here, and not
                    // an array
                    $config[$part] = new BaseObject;
                }

                $config = &$config[$part];
            }
            else {
                // we can go no further
                throw new E4xx_ConfigPathCannotBeExtended($pathToCreate, $pathSoFar, gettype($config));
            }

            // keep track of where we have been, for error reporting
            if (strlen($pathSoFar) > 0) {
                $pathSoFar = $pathSoFar . ".";
            }
            $pathSoFar = $pathSoFar . $part;
        }

        // if we get here, then we have extended the path as required
        return $config;
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
        if (FALSE === $raw) {
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
        if (is_object($json)) {
            $config = new BaseObject;
            $config->mergeFrom($json);
        }
        else {
            // *probably* an array, but it doesn't matter right now
            $config = $json;
        }

        // store the config
        $this->setConfig($config);
        $this->setName(basename($pathToFile, '.json'));
        $this->setFilename($pathToFile);

        // all done
    }

    /**
     * returns the name assigned to this config
     * @return string
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
}