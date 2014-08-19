<?php

/**
 * Copyright (c) 2013-present Mediasift Ltd
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
 * @package   Storyplayer/Prose
 * @author    Michael Heap <michael.heap@datasift.com>
 * @copyright 2013-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Prose;

/**
 * CleanupProcesses
 *
 * @uses CleanupProcesses
 * @author Michael Heap <michael.heap@datasift.com>
 */
class CleanupProcesses extends BaseCleanup
{

    public function startup()
    {
        return $this->pruneProcessList();
    }

    public function shutdown()
    {
        return $this->pruneProcessList();
    }

    /**
     * pruneProcessList
     *
     * Loop through our recorded processes and send them a `kill 0`
     * If they don't respond, they're already dead so remove them from the table
     *
     * @return void
     */
    private function pruneProcessList()
    {
        // shorthand
        $st = $this->st;

        // get the processes table, if we have one
        $table = $this->getTable();
        if (!$table) {
            return;
        }

        foreach ($table as $pid => $details) {
            if (!posix_kill($pid, 0)) {
                // process no longer running
                unset($table->$pid);
            }
        }

        $this->removeTablesIfEmpty();
        $st->saveRuntimeConfig();

        return;

        /*
                // shorthand
        $st     = $this->st;
        $output = $this->output;

        // do we have anything to shutdown?
        $screenSessions = $st->fromShell()->getAllScreenSessions();
        if (count($screenSessions) == 0) {
            // nothing to do
            return;
        }

        // if we get here, there are background jobs running
        echo "\n";
        if (count($screenSessions) == 1) {
            $output->logCliInfo("There is 1 background process still running");
        }
        else {
            $output->logCliInfo("There are " . count($screenSessions) . " background processes still running");
        }
        $output->logCliInfo("Use 'storyplayer list-processes' to see the list of background processes");
        $output->logCliInfo("Use 'storyplayer kill-processes' to stop any background processes");
        */
    }

}
