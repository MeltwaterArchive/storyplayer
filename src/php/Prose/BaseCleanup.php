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

namespace Prose;

use DataSift\Storyplayer\PlayerLib\StoryTeller;

/**
 * BaseCleanup
 *
 * @uses Prose
 * @author Michael Heap <michael.heap@datasift.com>
 */
abstract class BaseCleanup extends Prose
{

    /**
     * key
     * The key of the table we're working with
     *
     * @var string
     */
    protected $key;

    /**
     * table
     *
     * The actual table that we're working with
     *
     * @var \DataSift\Stone\ObjectLib\BaseObject
     */
    protected $table;

    /**
     * __construct
     *
     * @param StoryTeller $st The StoryTeller object
     * @param array $args Any arguments to be used in this Prose module
     *
     * @return parent::__construct
     */
    public function __construct(StoryTeller $st, $args = array())
    {
        // The key of the entry we're working with
        $this->key = $args[0];

        return parent::__construct($st, $args);
    }

    /**
     * startup
     *
     * The function to run before stories are run
     *
     * @return void
     */
    abstract public function startup();

    /**
     * shutdown
     *
     * The function to run after stories are run
     *
     * @return void
     */
    abstract public function shutdown();

    /**
     * return the table that our subclass needs to clean up
     *
     * @return null|\DataSift\Stone\ObjectLib\BaseObject
     */
    protected function getTable()
    {
        // shorthand
        $st = $this->st;

        // get the table, if we have one
        $runtimeConfig = $st->getRuntimeConfig();
        $key = $this->key;

        if (!isset($runtimeConfig->storyplayer, $runtimeConfig->storyplayer->tables, $runtimeConfig->storyplayer->tables->$key)) {
            return null;
        }

        // return the table
        return $runtimeConfig->storyplayer->tables->$key;
    }

    /**
     * removeTableIfEmpty
     *
     * Remove an entry in the runtime config if it is empty
     *
     * @return void
     */
    protected function removeTablesIfEmpty()
    {
        // shorthand
        $key = $this->key;
        $runtimeConfig = $this->st->getRuntimeConfig();

        // is the roles table now empty?
        if (count(get_object_vars($runtimeConfig->storyplayer->tables->$key)) == 0) {
            unset($runtimeConfig->storyplayer->tables->$key);
        }

        // did we just delete the last table?
        if (count(get_object_vars($runtimeConfig->storyplayer->tables)) == 0) {
            unset($runtimeConfig->storyplayer->tables);
        }

        // did we just remove the last entry from the storyplayer section
        // of the runtimeConfig?
        if (count(get_object_vars($runtimeConfig->storyplayer)) == 0) {
            unset($runtimeConfig->storyplayer);
        }
    }
}
