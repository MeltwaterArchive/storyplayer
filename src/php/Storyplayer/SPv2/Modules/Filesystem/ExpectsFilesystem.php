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

use Storyplayer\SPv2\Modules\Exceptions;
use Storyplayer\SPv2\Modules\Filesystem;
use Storyplayer\SPv2\Modules\Host\HostAwareModule;
use Storyplayer\SPv2\Modules\Log;

/**
 *
 * test the state of a (possibly remote) computer
 *
 * @category  Libraries
 * @package   Storyplayer/Modules/Host
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class ExpectsFilesystem extends HostAwareModule
{
    public function hasFileWithPermissions($filename, $owner, $group, $mode)
    {
        // shorthand
        $octMode = decoct($mode);

        // what are we doing?
        $log = Log::usingLog()->startAction("make sure file '{$filename}' exists on host '{$this->args[0]}' with permissions '{$octMode}' owned by '{$owner}:{$group}'");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // get the file details
        $details = Filesystem::fromHost($hostDetails->hostId)->getFileDetails($filename);

        // validate the details
        if ($details === null) {
            throw Exceptions::newExpectFailedException(__METHOD__, "'{$filename}' exists", "'{$filename}' does not exist");
        }

        if ($details->type != 'file') {
            throw Exceptions::newExpectFailedException(__METHOD__, "'{$filename}' is a file", "'{$filename}' is type '{$details->type}'");
        }

        if ($details->mode != $mode) {
            $theirOctMode = decoct($details->mode);
            throw Exceptions::newExpectFailedException(__METHOD__, "'{$filename}' has permissions '{$octMode}'", "'{$filename}' has permissions '{$theirOctMode}'");
        }

        if ($details->user != $owner || $details->group != $group) {
            throw Exceptions::newExpectFailedException(__METHOD__, "'{$filename}' has ownership '{$owner}:{$group}'", "'{$filename}' has ownership '{$details->user}:{$details->group}'");
        }

        // if we get here, then all is good
        $log->endAction();
    }

    public function hasFolderWithPermissions($folder, $owner, $group, $mode)
    {
        // shorthand
        $octMode = decoct($mode);

        // what are we doing?
        $log = Log::usingLog()->startAction("make sure folder '{$folder}' exists on host '{$this->args[0]}' with permissions '{$octMode}' owned by '{$owner}:{$group}'");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // get the file details
        $details = Filesystem::fromHost($hostDetails->hostId)->getFileDetails($folder);

        // validate the details
        if ($details === null) {
            throw Exceptions::newExpectFailedException(__METHOD__, "'{$folder}' exists", "'{$folder}' does not exist");
        }

        if ($details->type != 'dir') {
            throw Exceptions::newExpectFailedException(__METHOD__, "'{$folder}' is a file", "'{$folder}' is type '{$details->type}'");
        }

        if ($details->mode != $mode) {
            $theirOctMode = decoct($details->mode);
            throw Exceptions::newExpectFailedException(__METHOD__, "'{$folder}' has permissions '{$octMode}'", "'{$folder}' has permissions '{$theirOctMode}'");
        }

        if ($details->user != $owner || $details->group != $group) {
            throw Exceptions::newExpectFailedException(__METHOD__, "'{$folder}' has ownership '{$owner}:{$group}'", "'{$folder}' has ownership '{$details->user}:{$details->group}'");
        }

        // if we get here, then all is good
        $log->endAction();
    }
}
