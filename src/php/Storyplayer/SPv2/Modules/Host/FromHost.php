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
 * @package   Storyplayer/Modules/Host
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv2\Modules\Host;

use DataSift\Storyplayer\HostLib;
use DataSift\Storyplayer\OsLib;

use DataSift\Stone\DataLib\DataPrinter;
use DataSift\Stone\ObjectLib\BaseObject;

use GanbaroDigital\TextTools\Filters\FilterColumns;
use GanbaroDigital\TextTools\Filters\FilterForMatchingRegex;
use GanbaroDigital\TextTools\Filters\FilterForMatchingString;

use Storyplayer\SPv2\Modules\Exceptions;
use Storyplayer\SPv2\Modules\Filesystem;
use Storyplayer\SPv2\Modules\Log;
use Storyplayer\SPv2\Modules\Screen;
use StoryplayerInternals\SPv2\Modules\Deprecated;
use StoryplayerInternals\SPv2\Helpers\ManualUrls;

/**
 * get information about a given host
 *
 * @category  Libraries
 * @package   Storyplayer/Modules/Host
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class FromHost extends HostAwareModule
{
    /**
     * @return object
     */
    public function getDetails()
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("retrieve details for host '{$this->args[0]}'");

        // get the host details
        $hostDetails = $this->getHostDetails();

        // we already have details - are they valid?
        if (isset($hostDetails->invalidHost) && $hostDetails->invalidHost) {
            $msg = "there are no details about host '{$hostDetails->hostId}'";
            $log->endAction($msg);
            throw Exceptions::newActionFailedException(__METHOD__, $msg);
        }

        // return the details
        $log->endAction();
        return $hostDetails;
    }

    /**
     * is a host up and running?
     *
     * @return boolean
     */
    public function getHostIsRunning()
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("is host '{$this->args[0]}' running?");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // get an object to talk to this host
        $host = HostLib::getHostAdapter($this->st, $hostDetails->type);

        // if the box is running, it should have a status of 'running'
        $result = $host->isRunning($hostDetails);

        if (!$result) {
            $log->endAction("host is not running");
            return false;
        }

        // all done
        $log->endAction("host is running");
        return true;
    }

    /**
     * get the hostname for a host
     *
     * the returned hostname is suitable for use in HTTP/HTTPS URLs
     *
     * if we have been unable to determine the hostname for the host,
     * this will return the host's IP address instead
     *
     * @return string
     */
    public function getHostname()
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("get the hostname of host ID '{$this->args[0]}'");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // do we have a hostname?
        if (!isset($hostDetails->hostname)) {
            throw Exceptions::newActionFailedException(__METHOD__, "no hostname found for host ID '{$this->args[0]}'");
        }

        // all done
        $log->endAction("hostname is '{$hostDetails->hostname}'");
        return $hostDetails->hostname;
    }

    /**
     * get the IP address for a host
     *
     * @return string
     */
    public function getIpAddress()
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("get IP address of host '{$this->args[0]}'");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // all done
        $log->endAction("IP address is '{$hostDetails->ipAddress}'");
        return $hostDetails->ipAddress;
    }

    /**
     * @param  string $packageName
     * @return object
     */
    public function getInstalledPackageDetails($packageName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("get details for package '{$packageName}' installed on host '{$this->args[0]}'");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // get an object to talk to this host
        $host = OsLib::getHostAdapter($this->st, $hostDetails->osName);

        // get the information
        $return = $host->getInstalledPackageDetails($hostDetails, $packageName);

        // all done
        $log->endAction();
        return $return;
    }

    /**
     * @param  int $pid
     * @return bool
     */
    public function getPidIsRunning($pid)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("is process PID '{$pid}' running on host '{$this->args[0]}'?");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // get an object to talk to this host
        $host = OsLib::getHostAdapter($this->st, $hostDetails->osName);

        // get the information
        $return = $host->getPidIsRunning($hostDetails, $pid);

        // did it work?
        if ($return) {
            $log->endAction("'{$pid}' is running");
            return true;
        }

        $log->endAction("'{$pid}' is not running");
        return false;
    }

    /**
     * @param  string $processName
     * @return bool
     */
    public function getProcessIsRunning($processName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("is process '{$processName}' running on host '{$this->args[0]}'?");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // get an object to talk to this host
        $host = OsLib::getHostAdapter($this->st, $hostDetails->osName);

        // get the information
        $return = $host->getProcessIsRunning($hostDetails, $processName);

        // did it work?
        if ($return) {
            $log->endAction("'{$processName}' is running");
            return true;
        }

        $log->endAction("'{$processName}' is not running");
        return false;
    }

    /**
     * @param  string $processName
     * @return int
     */
    public function getPid($processName)
    {
        // log some info to the user
        $log = Log::usingLog()->startAction("get id of process '{$processName}' running on VM '{$this->args[0]}'");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // get an object to talk to this host
        $host = OsLib::getHostAdapter($this->st, $hostDetails->osName);

        // get the information
        $return = (int)$host->getPid($hostDetails, $processName);

        // success
        $log->endAction("pid is '{$return}'");
        return $return;
    }

    /**
     * @return string
     */
    public function getSshUsername()
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("get username to use with SSH to host '{$this->args[0]}'");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // get the information
        $return = $hostDetails->sshUsername;

        // all done
        $log->endAction("username is '{$return}'");
        return $return;
    }

    /**
     * @return string
     */
    public function getSshKeyFile()
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("get key file to use with SSH to host '{$this->args[0]}'");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // get the information
        $return = $hostDetails->sshKeyFile;

        // all done
        $log->endAction("key file is '{$return}'");
        return $return;
    }

    /**
     * @param  string $sessionName
     * @return bool
     */
    public function getIsScreenRunning($sessionName)
    {
        return $this->getScreenIsRunning($sessionName);
    }

    /**
     * @deprecated since v2.4.0
     */
    public function getScreenIsRunning($sessionName)
    {
        Deprecated::fireDeprecated(__METHOD__, "v2.4.0", ManualUrls::HOST_MODULE_BREAKUP);
        return Screen::fromHost($this->args[0])->getScreenIsRunning($sessionName);
    }

    /**
     * @deprecated since v2.4.0
     */
    public function getScreenSessionDetails($sessionName)
    {
        Deprecated::fireDeprecated(__METHOD__, "v2.4.0", ManualUrls::HOST_MODULE_BREAKUP);
        return Screen::fromHost($this->args[0])->getScreenSessionDetails($sessionName);
    }

    /**
     * @deprecated since v2.4.0
     */
    public function getAllScreenSessions()
    {
        Deprecated::fireDeprecated(__METHOD__, "v2.4.0", ManualUrls::HOST_MODULE_BREAKUP);
        return Screen::fromHost($this->args[0])->getAllScreenSessions();
    }

    /**
     * @param  string $appName
     * @return mixed
     */
    public function getAppSettings($appName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("get settings for '{$appName}' from host '{$this->args[0]}'");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // do we have any app settings?
        if (!isset($hostDetails->appSettings, $hostDetails->appSettings->$appName)) {
            $log->endAction("setting does not exist :(");
            throw Exceptions::newActionFailedException(__METHOD__);
        }

        // yes we do
        $value = $hostDetails->appSettings->$appName;

        // log the settings
        $printer  = new DataPrinter();
        $logValue = $printer->convertToString($value);
        $log->endAction("settings for '{$appName}' are '{$logValue}'");

        // all done
        return $value;
    }

    /**
     * @param  string $path
     * @param  string|null $settingName
     * @return mixed
     */
    public function getAppSetting($path, $settingName = null)
    {
        // are we operating in legacy mode (for DataSift), or are we using
        // the new dot.notation.support that we want everywhere in v2?
        $parts = explode(".", $path);
        if (count($parts) === 1 && $settingName !== null) {
            return $this->getLegacyAppSetting($path, $settingName);
        }

        // if we get here, then we are using the new dot.notation.support

        // what are we doing?
        $log = Log::usingLog()->startAction("get appSetting '{$path}' from host '{$this->args[0]}'");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // do we have any app settings?
        if (!isset($hostDetails->appSettings)) {
            $msg = "host has no appSettings at all";
            $log->endAction($msg);
            throw Exceptions::newActionFailedException(__METHOD__, $msg);
        }

        // do we have the setting that we want?
        if (!$hostDetails->appSettings->hasData($path)) {
            $msg = "host does not have appSetting '{$path}'";
            throw Exceptions::newActionFailedException(__METHOD__, $msg);
        }

        // success
        $data = $hostDetails->appSettings->getData($path);

        // all done
        $log->endAction([ "setting is", $data ]);
        return $data;
    }

    /**
     * @param  string $appName
     * @param  string $settingName
     * @return mixed
     */
    protected function getLegacyAppSetting($appName, $settingName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("get $settingName for '{$appName}' from host '{$this->args[0]}'");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // do we have any app settings?
        if (!isset($hostDetails->appSettings)) {
            $log->endAction("host has no appSettings at all");
            throw Exceptions::newActionFailedException(__METHOD__);
        }
        if (!isset($hostDetails->appSettings->$appName)) {
            $log->endAction("host has no appSettings for {$appName}");
            throw Exceptions::newActionFailedException(__METHOD__);
        }
        if (!isset($hostDetails->appSettings->$appName->$settingName)) {
            $log->endAction("host has no appSetting '{$settingName}' for {$appName}");
            throw Exceptions::newActionFailedException(__METHOD__);
        }

        // yes we do
        $value = $hostDetails->appSettings->$appName->$settingName;

        // log the settings
        $printer  = new DataPrinter();
        $logValue = $printer->convertToString($value);
        $log->endAction("setting for '{$appName}' is '{$logValue}'");

        // all done
        return $value;
    }

    /**
     * @param  string $path
     * @return mixed
     */
    public function getStorySetting($path)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("get storySetting '{$path}' from host '{$this->args[0]}'");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // do we have any app settings?
        if (!isset($hostDetails->storySettings)) {
            $msg = "host has no storySettings at all";
            $log->endAction($msg);
            throw Exceptions::newActionFailedException(__METHOD__, $msg);
        }

        // do we have the setting that we want?
        if (!$hostDetails->storySettings->hasData($path)) {
            $msg = "host does not have storySetting '{$path}'";
            throw Exceptions::newActionFailedException(__METHOD__, $msg);
        }

        // success
        $data = $hostDetails->storySettings->getData($path);

        // all done
        $log->endAction([ "setting is", $data ]);
        return $data;
    }

    /**
     * @deprecated since v2.4.0
     */
    public function downloadFile($sourceFilename, $destFilename)
    {
        Deprecated::fireDeprecated(__METHOD__, "v2.4.0", ManualUrls::HOST_MODULE_BREAKUP);
        return Filesystem::fromHost($this->args[0])->downloadFile($sourceFilename, $destFilename);
    }

    /**
     * @deprecated since v2.4.0
     */
    public function getFileDetails($filename)
    {
        Deprecated::fireDeprecated(__METHOD__, "v2.4.0", ManualUrls::HOST_MODULE_BREAKUP);
        return Filesystem::fromHost($this->args[0])->getFileDetails($filename);
    }

    /**
     * which local folder do we need to be in when working with this host?
     *
     * @return string
     */
    public function getLocalFolder()
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("get local folder for host '{$this->args[0]}'");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // does it have a folder defined?
        if (isset($hostDetails->dir)) {
            $retval = $hostDetails->dir;
        }
        else {
            $retval = getcwd();
        }

        // all done
        $log->endAction($retval);
        return $retval;
    }
}
