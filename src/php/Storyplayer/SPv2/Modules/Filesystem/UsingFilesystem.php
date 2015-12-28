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

use DataSift\Storyplayer\OsLib;
use DataSift\Stone\ObjectLib\BaseObject;

use Storyplayer\SPv2\Modules\Exceptions;
use Storyplayer\SPv2\Modules\Host\HostAwareModule;
use Storyplayer\SPv2\Modules\Log;

/**
 * do things with (possibly remote) filesystems
 *
 * @category  Libraries
 * @package   Storyplayer/Modules/Filesystem
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class UsingFilesystem extends HostAwareModule
{
    public function uploadFile($sourceFilename, $destFilename)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("upload file '{$sourceFilename}' to '{$this->args[0]}':'{$destFilename}'");

        // does the source file exist?
        if (!is_file($sourceFilename)) {
            $log->endAction("file '{$sourceFilename}' not found :(");
            throw Exceptions::newActionFailedException(__METHOD__);
        }

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // get an object to talk to this host
        $host = OsLib::getHostAdapter($this->st, $hostDetails->osName);

        // upload the file
        $result = $host->uploadFile($hostDetails, $sourceFilename, $destFilename);

        // did the command used to upload succeed?
        if ($result->didCommandFail()) {
            $msg = "upload failed with return code '{$result->returnCode}' and output '{$result->output}'";
            $log->endAction($msg);
            throw Exceptions::newActionFailedException(__METHOD__, $msg);
        }

        // all done
        $log->endAction();
        return $result;
    }
}
