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
use DataSift\Stone\ExceptionsLib\LegacyErrorCatcher;

/**
 * Base class for our config file loaders
 *
 * @category  Libraries
 * @package   Stone/ConfigLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
abstract class BaseConfigLoader
{
    /**
     * the name of the app that we are loading configs for
     * @var string
     */
    private $appName = null;

    /**
     * the type of config file that we're loading (eg .json files)
     * @var string
     */
    private $configSuffix = "";

    /**
     * a list of places to look for our default configs
     *
     * we look through these in turn, and only load the first one that
     * we find
     *
     * @var array
     */
    private $defaultConfigFilePaths = array();

    /**
     * the basename of the config file to look for
     *
     * this is normally $appName.$configSuffix
     *
     * @var string
     */
    private $configFileBasename = null;

    /**
     * instantiate the config loader
     *
     * @param string $appName
     *        the name of your app
     *
     *        this is used to work out the name of the folder(s) to look
     *        inside for the default config file, and what the name of
     *        the config file itself should be
     *
     * @param string $topDir
     *        the INSTALL_PREFIX of your app, or the top of your source
     *        tree
     *
     *        the config loaders will look in the following places
     *        for your app's default config files (in this order):
     *
     *        {$topDir}
     *        {$topDir}/etc/
     *        {$topDir}/src/etc
     *        /etc/{$appName}/
     *
     * @param string $configSuffix
     *        the filename suffix to use for config files
     *
     *        this will be appended onto any filenames that we search for,
     *        and also used when looking for the default config file
     *        like this:
     *
     *        {$appName}.{$configSuffix}
     */
    public function __construct($appName, $topDir, $configSuffix)
    {
        // remember the name of the app we're loading configs for
        $this->appName = $appName;

        // remember the suffix to append to our config files when
        // we're looking for them
        $this->configSuffix = $configSuffix;

        // setup our list of directories to search for the app's
        // default config file
        $this->initDefaultConfigFilePaths($topDir);

        // work out what the basename (the filename with no path) of
        // our default config file should be
        $this->initConfigFileBasename();
    }

    /**
     * create the initial list of folders to search for our default
     * config file
     *
     * @param  string $topDir
     *         the location that our app is running from
     * @return void
     */
    protected function initDefaultConfigFilePaths($topDir)
    {
        $this->defaultConfigFilePaths = array(
            $topDir,
            $topDir . '/etc',
            $topDir . '/src/etc',
            $topDir . '/src/main/etc',
            $topDir . '/src/main/config',
            "/etc/{$this->appName}"
        );
    }

    /**
     * generate the default name of this app's config file
     *
     * @return void
     */
    protected function initConfigFileBasename()
    {
        $this->configFileBasename = $this->appName . '.' . $this->configSuffix;
    }

    /**
     * find the default config file on disk and load it
     *
     * @return LoadedConfig
     *         our loaded config
     */
    public function loadDefaultConfig()
    {
        // this will contain the loaded config when we are finished
        $config    = new LoadedConfig;

        // load the expected config from disk
        $newConfig = $this->loadFileFromDefaultPaths($this->configFileBasename);

        // copy it across to our container
        //
        // we do this because our container gives us features that
        // stdClass does not
        $config->mergeFrom($newConfig);

        // all done - return the container to the caller
        return $config;
    }

    /**
     * find any per-user config file, and merge it with the app's default
     * config that we've already loaded
     *
     * @param  LoadedConfig $config
     *         the config loaded by $this->loadDefaultConfig()
     * @return LoadedConfig
     *         $config + any user-specific config that we found
     */
    public function loadUserConfig(LoadedConfig $config)
    {
        // where is the user's home directory?
        $home = getenv("HOME");
        if (empty($home)) {
            // we don't know ... we cannot continue
            return;
        }

        // where will the user's defaults file be?
        $filename = "{$home}/.{$this->appName}/{$this->configFileBasename}";

        // does it exist?
        if (!file_exists($filename)) {
            // no - nothing more to do
            return;
        }

        // we have a file to load
        $newConfig = $this->loadConfigFile($filename);

        // merge the user's defaults with the global defaults
        $config->mergeFrom($newConfig);

        // all done
    }

    /**
     * find any per-environment or per-repo config file, and merge it with
     * the app's config that we've already loaded
     *
     * @param  LoadedConfig $config
     *         the config loaded by $this->loadDefaultConfig() or
     *         $this->loadUserConfig()
     * @param  string $basename
     *         the name of the file to look for
     * @return LoadedConfig
     *         $config + any additional config that we loaded
     */
    public function loadAdditionalConfig(LoadedConfig $config, $basename)
    {
        // load the additional config from disk
        $newConfig = $this->loadFileFromDefaultPaths($basename . '.' . $this->configSuffix);

        // merge it into the existing config
        $config->mergeFrom($newConfig);

        // all done
    }

    /**
     * find any previously saved state, and load it
     *
     * @return LoadedConfig
     *         the state that we found (if any)
     */
    public function loadRuntimeConfig()
    {
        // where is the user's home directory?
        $home = getenv("HOME");
        if (empty($home)) {
            // we don't know ... we cannot continue
            return new LoadedConfig();
        }

        // where will the runtime file be?
        $filename = "{$home}/.{$this->appName}/runtime.{$this->configSuffix}";

        // does it exist?
        if (!file_exists($filename)) {
            // no - nothing more to do
            return new LoadedConfig();
        }

        // we have a file to load
        $newConfig = $this->loadConfigFile($filename);

        // convert it into a LoadedConfig object
        $config = new LoadedConfig();
        $config->mergeFrom($newConfig);

        // all done
        return $config;
    }

    /**
     * save state to disk, to reload the next time we run the app
     *
     * the state is always saved into the user's dotfiles
     *
     * @param  LoadedConfig $config
     *         the state to save to disk
     * @return void
     */
    public function saveRuntimeConfig(LoadedConfig $config)
    {
        // where is the user's home directory?
        $home = getenv("HOME");
        if (empty($home)) {
            // we don't know ... we cannot continue
            return;
        }

        // which folder will we store the data in?
        $filename = "{$home}/.{$this->appName}";

        // does it exist?
        if (!file_exists($filename)) {
            // no - create it
            $success = mkdir($filename, 0700, true);

            // did it work?
            if (!$success) {
                throw new E5xx_CannotCreateRuntimeConfigFolder($filename);
            }
        }

        // where will the runtime file be?
        $filename .= "/runtime.{$this->configSuffix}";

        // convert the config
        $data = $this->encodeConfig($config);

        // write out the data
        file_put_contents($filename, $data);

        // all done
    }

    /**
     * search our list of default paths, and load the first config file
     * that we find
     *
     * throws an exception if we could not find any config file
     *
     * @param  string $configName
     *         name of the config file to search for
     * @return LoadedConfig
     *         the config that we loaded
     */
    protected function loadFileFromDefaultPaths($configName)
    {
        // a list of everywhere that we have looked, in case we can't find
        // the file at all
        $searchedPaths = array();

        // load the first file that we find
        foreach ($this->defaultConfigFilePaths as $filename) {
            // remember this, in case we have to throw an error
            $searchedPaths[] = $filename;

            // build up the full filename
            $filename .= '/' . $configName;

            // does it exist?
            if (file_exists($filename)) {
                // yes - LOAD IT
                $config = $this->loadConfigFile($filename);
                break;
            }

            // is there a .dist version of the file to load instead?
            if (file_exists($filename . '.dist')) {
                // yes - LOAD IT
                $config = $this->loadConfigFile($filename . '.dist');
                break;
            }
        }

        // did we find one?
        if (!isset($config)) {
            // no, we did not
            throw new E5xx_ConfigFileNotFound($configName, $searchedPaths);
        }

        // if we get here, then we successfully loaded some config
        return $config;
    }

    /**
     * safely load the contents of a config file
     *
     * throws an exception if the file could not be found or could not
     * be decoded into a PHP object tree
     *
     * @param  string $filename
     *         the full (or relative) path to the file that we wish to load
     * @return stdClass
     *         the loaded config
     */
    protected function loadConfigFile($filename)
    {
        // make sure the file exists
        $this->requireConfigFile($filename);

        // if we get here, we have a file that we can read

        // we could potentially get legacy errors here, so best to wrap
        // things up
        $wrapper = new LegacyErrorCatcher();
        $configLoader = $this;
        return $wrapper->callUserFuncArray(function() use($filename, $configLoader) {
            // open the file
            $rawConfig = @file_get_contents($filename);
            if (!$rawConfig || !is_string($rawConfig) || empty($rawConfig))
            {
                throw new E5xx_InvalidConfigFile("Config file '$filename' is empty or unreadable");
            }

            // decode the contents
            $config = $configLoader->decodeLoadedFile($rawConfig);

            // did it work?
            if (!is_object($config))
            {
                throw new E5xx_InvalidConfigFile("Config file '$filename' contains invalid JSON");
            }

            // if we get here, we've successfully loaded the config
            return $config;
        });
    }

    /**
     * make sure that the config file exists
     *
     * if there are any problems, we throw Exceptions
     *
     * @param  string $filename the config file to test
     * @return void
     */
    protected function requireConfigFile($filename)
    {
        if (!file_exists($filename))
        {
            throw new E5xx_InvalidConfigFile("Config file '$filename' is missing", 500);
        }
        if (!is_readable($filename))
        {
            throw new E5xx_InvalidConfigFile("Config file '$filename' cannot be opened for reading", 500);
        }
    }

    /**
     * decode the loaded config file into a tree of objects
     *
     * override this in your format-specific config file loader
     *
     * @param  string $rawConfig
     *         the raw contents of the config file that has been loaded
     *
     * @return stdClass
     *         the results of decoding the config file
     */
    abstract public function decodeLoadedFile($rawConfig);

    /**
     * encode a tree of objects into a string suitable for saving into
     * a config file on disk
     *
     * @param  stdClass $config
     *         the config to be encoded
     *
     * @return string
     *         the encoded data
     */
    abstract public function encodeConfig(stdClass $config);
}