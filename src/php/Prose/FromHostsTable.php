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
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Prose;

/**
 * retrieve data from the internal hosts table
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class FromHostsTable extends Prose
{
    /**
     * entryKey
     * The key that this table interacts with in the RuntimeConfig
     *
     * @var string
     */
    protected $entryKey = "hosts";


    /**
     * getHostsTable
     *
     *
     * @return object The hosts table
     */
    public function getHostsTable()
    {
        // what are we doing?
        $log = usingLog()->startAction("get the hosts table for the current test environment");

        // which test environment are we working with?
        $testEnvName = $this->st->getTestEnvironmentName();

        // get the table
        $table = fromRuntimeTable($this->entryKey)->getGroupFromTable($testEnvName);

        // all done
        $log->endAction();
        return $table;
    }

    /**
     * getDetailsForHost
     *
     * @param string $hostId
     *        The host we're looking for
     *
     * @return object
     *         Details about $hostId
     */
    public function getDetailsForHost($hostId)
    {
        // what are we doing?
        $log = usingLog()->startAction("get details for host '{$hostId}' from the current test environment");

        // which test environment are we working with?
        $testEnvName = $this->st->getTestEnvironmentName();

        // get the details
        $hostDetails = fromRuntimeTable($this->entryKey)->getDetailsFromGroup($testEnvName, $hostId);

        // all done
        $log->endAction();
        return $hostDetails;
    }
}
