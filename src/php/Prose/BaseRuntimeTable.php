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

use DataSift\Stone\ObjectLib\BaseObject;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

/**
 * BaseRuntimeTable
 *
 * @uses Prose
 * @author Michael Heap <michael.heap@datasift.com>
 */
class BaseRuntimeTable extends Prose
{

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
        if (!isset($args[0])){
            throw new E4xx_MissingArgument(__METHOD__, "You must provide a table name to ".get_class($this)."::__construct");
        }

        // normalise
        //
        // in Storyplayer v1.x, we used ucfirst(), but on reflection,
        // I strongly prefer lcfirst() now that we've introduced support
        // for retrieving data via the template engine
        $args[0] = lcfirst($args[0]);

        return parent::__construct($st, $args);
    }

    /**
     * getAllTables
     *
     * Return our tables config that we can use for
     * in place editing
     *
     * @return BaseObject
     */
    public function getAllTables()
    {
        // get the runtime config
        $runtimeConfig = $this->st->getRuntimeConfig();
        $runtimeConfigManager = $this->st->getRuntimeConfigManager();

        return $runtimeConfigManager->getAllTables($runtimeConfig);
    }
}



