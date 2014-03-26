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
 * @package   Storyplayer/Phases
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Phases;

use DataSift\StoryPlayer\PlayerLib\StoryTeller;
use DataSift\StoryPlayer\Prose\E5xx_NoMatchingActions;

/**
 * a helper for phases that cleanup our persistent tables
 *
 * @category  Libraries
 * @package   Storyplayer/Phases
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

class TableHandlersHelper
{
	/**
	 * @param string $type
	 */
	public function runHandlers(StoryTeller $st, $type)
	{
		// shorthand
		$output = $st->getOutput();

        // Do we have any persistent tables to cleanup?
        $runtimeConfig = $st->getRuntimeConfig();
        if (!isset($runtimeConfig->storyplayer, $runtimeConfig->storyplayer->tables)){
            // there are no tables at all
            return;
        }

        // if we get here, then we know that there are persistent
        // tables that we need to cleanup

        // this will keep track of any tables that have no associated
        // cleanup handler
        $missingCleanupHandlers = [];

        // Take a look at all of our process list tables
        foreach ($runtimeConfig->storyplayer->tables as $key => $value) {
            $className = "cleanup".ucfirst($key);
            try {
                $st->$className($key, $value)->$type();
                $st->$className($key, $value)->removeTableIfEmpty();
            } catch(E5xx_NoMatchingActions $e){
                // We don't know about a cleanup module for this, SHOUT LOUDLY
                $missingCleanupHandlers[] = "Missing cleanup module for '{$key}'".PHP_EOL;
            }
        }

        // Now we've cleaned everything up, save the runtime config
        $st->saveRuntimeConfig();

        // If we have any missing cleanup handlers, output it to the screen
        // and exit with an error code
        if (count($missingCleanupHandlers)) {
            foreach ($missingCleanupHandlers as $msg) {
                $output->logCliError($msg);
            }
            exit(1);
        }
	}
}