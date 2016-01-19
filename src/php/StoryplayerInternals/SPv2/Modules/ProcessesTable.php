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
 * @package   StoryplayerInternals/Modules/Deprecated
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace StoryplayerInternals\SPv2\Modules;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use StoryplayerInternals\SPv2\Modules\ProcessesTable\CleanupProcessesTable;
use StoryplayerInternals\SPv2\Modules\ProcessesTable\FromProcessesTable;
use StoryplayerInternals\SPv2\Modules\ProcessesTable\ExpectsProcessesTable;
use StoryplayerInternals\SPv2\Modules\ProcessesTable\UsingProcessesTable;

class ProcessesTable
{
    /**
     * use this to cleanup the processes table when we're shutting down
     *
     * @param  string $key
     *         the table to cleanup
     * @return CleanupProcessesTable
     */
    public static function cleanupProcessesTable($key)
    {
        return new CleanupProcessesTable(StoryTeller::instance(), [$key]);
    }

    /**
     * use this to expect that the internal table of child processes is in
     * a given state
     *
     * @return ExpectsProcessesTable
     */
    public static function expectsProcessesTable()
    {
        return new ExpectsProcessesTable(StoryTeller::instance());
    }

    /**
     * use this to inspect the state of the internal table of child processes
     *
     * @return FromProcessesTable
     */
    public static function fromProcessesTable()
    {
        return new FromProcessesTable(StoryTeller::instance());
    }

    /**
     * use this to change the state of the internal table of child processes
     *
     * @return UsingProcessesTable
     */
    public static function usingProcessesTable()
    {
        return new UsingProcessesTable(StoryTeller::instance());
    }
}
