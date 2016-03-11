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
 * @package   Storyplayer/Modules/Filesystem
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv2\Modules\Filesystem;

use DataSift\Storyplayer\HostLib;
use DataSift\Storyplayer\OsLib;

use DataSift\Stone\DataLib\DataPrinter;
use DataSift\Stone\ObjectLib\BaseObject;

use GanbaroDigital\TextTools\Filters\FilterColumns;
use GanbaroDigital\TextTools\Filters\FilterForMatchingRegex;
use GanbaroDigital\TextTools\Filters\FilterForMatchingString;

use Storyplayer\SPv2\Modules\Exceptions;
use Storyplayer\SPv2\Modules\Host\HostAwareModule;
use Storyplayer\SPv2\Modules\Log;

/**
 * get information about a given host
 *
 * @category  Libraries
 * @package   Storyplayer/Modules/Filesystem
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class FromFilesystem extends HostAwareModule
{
    /**
     * @param  string $sourceFilename
     * @param  string $destFilename
     * @return \DataSift\Storyplayer\CommandLib\CommandResult
     */
    public function downloadFile($sourceFilename, $destFilename)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("download file '{$this->args[0]}':'{$sourceFilename}' to '{$destFilename}'");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // get an object to talk to this host
        $host = OsLib::getHostAdapter($this->st, $hostDetails->osName);

        // upload the file
        $result = $host->downloadFile($hostDetails, $sourceFilename, $destFilename);

        // did the command used to upload succeed?
        if ($result->didCommandFail()) {
            $msg = "download failed with return code '{$result->returnCode}' and output '{$result->output}'";
            $log->endAction($msg);
            throw Exceptions::newActionFailedException(__METHOD__, $msg);
        }

        // all done
        $log->endAction();
        return $result;
    }

    public function getTmpFileName()
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("generate a temporary filename");

        // create it
        $filename = tempnam(null, 'storyplayer-data-');

        // log it
        $log->endAction("'{$filename}'");

        // all done
        return $filename;
    }

    /**
     * @param  string $filename
     * @return object
     */
    public function getFileDetails($filename)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("get details for '{$filename}' on host '{$this->args[0]}'");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // get an object to talk to this host
        $host = OsLib::getHostAdapter($this->st, $hostDetails->osName);

        // get the details
        $details = $host->getFileDetails($hostDetails, $filename);

        // all done
        $log->endAction();
        return $details;
    }
}
