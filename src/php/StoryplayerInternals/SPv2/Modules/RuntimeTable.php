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
 * @package   StoryplayerInternals/Modules/RuntimeTable
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace StoryplayerInternals\SPv2\Modules;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use StoryplayerInternals\SPv2\Modules\RuntimeTable\ExpectsRuntimeTable;
use StoryplayerInternals\SPv2\Modules\RuntimeTable\FromRuntimeTable;
use StoryplayerInternals\SPv2\Modules\RuntimeTable\FromRuntimeTables;
use StoryplayerInternals\SPv2\Modules\RuntimeTable\UsingRuntimeTable;

/**
 * Module for working with Storyplayer's internal state, known as the
 * runtime tables.
 */
class RuntimeTable
{
    /**
     * returns the ExpectsRuntimeTable bank of operations
     *
     * @param string $tableName
     *        which runtime table do we want to operate on?
     * @return ExpectsRuntimeTable
     */
    public static function expectsRuntimeTable($tableName)
    {
        return new ExpectsRuntimeTable(StoryTeller::instance(), [$tableName]);
    }

    /**
     * returns the FromRuntimeTable bank of operations
     *
     * @param string $tableName
     *        which runtime table do we want to operate on?
     * @return FromRuntimeTable
     */
    public static function fromRuntimeTable($tableName)
    {
        return new FromRuntimeTable(StoryTeller::instance(), [$tableName]);
    }

    /**
     * returns the FromRuntimeTables bank of operations
     *
     * @return FromRuntimeTables
     */
    public static function fromRuntimeTables()
    {
        return new FromRuntimeTables(StoryTeller::instance());
    }

    /**
     * returns the UsingRuntimeTable bank of operations
     *
     * @param string $tableName
     *        which runtime table do we want to operate on?
     * @return UsingRuntimeTable
     */
    public static function usingRuntimeTable($tableName)
    {
        return new UsingRuntimeTable(StoryTeller::instance(), [$tableName]);
    }
}
