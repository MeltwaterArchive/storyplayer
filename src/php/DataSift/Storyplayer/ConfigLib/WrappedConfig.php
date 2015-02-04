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

use Twig_Environment;
use Twig_Loader_String;

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
     * @var BaseObject|array
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
        // special case - we treat objects and arrays differently to other
        // data types
        if (!is_object($dataToMerge) && !is_array($dataToMerge)) {
            // we just need to set the data instead
            $this->setData($path, $dataToMerge);
            return;
        }

        // get to where we need to be
        $leaf =& $this->getPath($path, true);

        // if we get here, then we know where we are adding the new
        // data
        if (is_object($leaf)) {
            $leaf->mergeFrom($dataToMerge);
        }
        else if (is_array($leaf)) {
            $leaf = array_merge($leaf, $dataToMerge);
        }
        else {
            throw new E4xx_ConfigPathCannotBeExtended($path, $path, gettype($leaf));
        }

        // all done
    }

    /**
     * expand any variables in $this
     *
     * @param  array|object $baseConfig
     *         the config to use for expanding variables (optional)
     * @return array|object
     *         a copy of the config stored in $this, with any Twig
     *         variables expanded
     */
    public function getExpandedConfig($baseConfig = null)
    {
        return $this->expandData($this->config, $baseConfig);
    }

    /**
     * expand any piece of data
     *
     * @param  mixed $dataToExpand
     *         the data to run through Twig
     * @param  array|object $baseConfig
     *         the config to use for expanding variables (optional)
     * @return array|object
     *         a copy of the config stored in $this, with any Twig
     *         variables expanded
     */
    protected function expandData($dataToExpand, $baseConfig = null)
    {
        // special case - is the data expandable in the first place?
        if (!is_object($dataToExpand) && !is_array($dataToExpand) && !is_string($dataToExpand)) {
            return $dataToExpand;
        }

        // we're going to use Twig to expand any parameters in our
        // config
        //
        // this seems horribly inefficient, but it does work reliably
        //
        // unfortunately, we cannot build any sort of cache, because we
        // have absolutely no way of knowing if $this->config has changed
        // at all
        $loader = new Twig_Loader_String();
        $templateEngine   = new Twig_Environment($loader);

        // Twig is a template engine. it needs a text string to operate on
        $configString = json_encode($dataToExpand);

        // Twig needs an array of data to expand variables from
        if ($baseConfig === null) {
            $baseConfig = $this->config;
        }
        $varData = json_decode(json_encode($baseConfig), true);

        // use Twig to expand any config variables
        $raw = $templateEngine->render($configString, $varData);
        $expandedData = json_decode($raw);

        // make sure we have our handy BaseObject, because it does nice
        // things like throw exceptions when someone tries to access an
        // attribute that does not exist
        if (is_object($expandedData)) {
            $tmp = new BaseObject();
            $tmp->mergeFrom($expandedData);
            $expandedData = $tmp;
        }
        else if (is_array($expandedData)) {
            foreach (array_keys($expandedData) as $key) {
                if (is_object($expandedData[$key])) {
                    $tmp = new BaseObject();
                    $tmp->mergeFrom($expandedData[$key]);
                    $expandedData[$key] = $tmp;
                }
            }
        }

        // all done
        return $expandedData;
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
        // special case
        if (empty($path)) {
            return $this->config;
        }

        $retval = $this->getPath($path);
        $retval = $this->expandData($retval);

        return $retval;
    }

    protected function &getPath($path, $expandPath = false)
    {
        // special case
        if (empty($path)) {
            return $this->config;
        }

        // this is where we start from
        $retval = $this->config;

        // this is where we have been so far, for error-reporting
        // purposes
        $pathSoFar = [];

        // walk down the path
        $parts = explode(".", $path);
        foreach ($parts as $part)
        {
            if (is_object($retval)) {
                if (isset($retval->$part)) {
                    $retval = &$retval->$part;
                }
                else if ($expandPath) {
                    $retval->$part = new BaseObject;
                    $retval = &$retval->$part;
                }
                else {
                    throw new E4xx_ConfigPathNotFound($path);
                }
            }
            else if (is_array($retval)) {
                if (isset($retval[$part])) {
                    $retval = &$retval[$part];
                }
                else if ($expandPath) {
                    $retval[$part] = new BaseObject;
                    $retval = &$retval[$part];
                }
                else {
                    throw new E4xx_ConfigPathNotFound($path);
                }
            }
            else {
                // we can go no further
                if ($expandPath) {
                    throw new E4xx_ConfigPathCannotBeExtended($path, implode('.', $pathSoFar), gettype($retval));
                }
                else {
                    throw new E4xx_ConfigPathNotFound($path);
                }
            }

            // remember where we have been, in case we need to report
            // and error soon
            $pathSoFar[] = $part;
        }

        // if we get here, we have walked the whole path
        return $retval;
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
        $retval = $this->getPath($path);

        if (!is_array($retval)) {
            throw new E4xx_ConfigDataNotAnArray($path);
        }

        $retval = (array)$this->expandData($retval);

        return $retval;
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
        $retval = $this->getPath($path);

        if (!is_object($retval)) {
            throw new E4xx_ConfigDataNotAnObject($path);
        }

        $retval = $this->expandData($retval);

        return $retval;
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
        // special case
        if (empty($path)) {
            return true;
        }

        // walk down the path
        $parts = explode(".", $path);

        // this is where we start from
        $retval = $this->getExpandedConfig();

        foreach ($parts as $part)
        {
            if (is_object($retval)) {
                if (isset($retval->$part)) {
                    $retval = $retval->$part;
                }
                else {
                    return false;
                }
            }
            else if (is_array($retval)) {
                if (isset($retval[$part])) {
                    $retval = $retval[$part];
                }
                else {
                    return false;
                }
            }
            else {
                // we can go no further
                return false;
            }
        }

        // if we get here, we have walked the whole path
        return true;
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
        // special case - empty path
        if (empty($path)) {
            $this->config = $data;
            return;
        }

        // walk down the path
        $parts = explode(".", $path);
        $lastPart = end($parts);
        $parts = array_slice($parts, 0, count($parts) - 1);

        // create the path
        $leaf =& $this->getPath(implode(".", $parts), true);

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
     * remove data using a dot.notation.path
     *
     * @param  string $path
     *         the dot.notation.path to use to navigate
     *
     * @return void
     */
    public function unsetData($path)
    {
        // walk down the path
        $parts = explode(".", $path);
        $lastPart = end($parts);
        $parts = array_slice($parts, 0, count($parts) - 1);

        // this is where we start from
        $retval =& $this->getPath(implode(".", $parts));

        // if we get here, we have walked the whole path, and are ready
        // to unset the value
        if (is_object($retval)) {
            if (isset($retval->$lastPart)) {
                unset($retval->$lastPart);
            }
            else {
                throw new E4xx_ConfigPathNotFound($path);
            }
        }
        else if (is_array($retval)) {
            if (isset($retval[$lastPart])) {
                unset($retval[$lastPart]);
            }
            else {
                throw new E4xx_ConfigPathNotFound($path);
            }
        }
        else {
            throw new E4xx_ConfigPathNotFound($path);
        }
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
