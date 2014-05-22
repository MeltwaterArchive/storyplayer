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
 * @author    Shweta Saikumar <shweta.saikumar@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\HostLib;
use DataSift\Storyplayer\OsLib;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Stone\ObjectLib\BaseObject;

/**
 * get information about a program running under supervisor
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Shweta Saikumar <shweta.saikumar@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class FromSupervisor extends HostBase
{
	public function getProgramIsRunning($programName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("is program '{$programName}' running under supervisor on host '{$this->hostDetails->name}'?");
       
        //run the supervisorctl command
        $result = $st->usingHost($this->hostDetails->name)->runCommandAndIgnoreErrors("sudo supervisorctl status |egrep '^$programName' | awk '{print \$2}'");

        // did the command succeed?
		if ($result->didCommandFail()) {
			$msg = "command failed with return code '{$result->returnCode}' and output '{$result->output}'";
			$log->endAction($msg);
			return false;
		}

		// all done
		$log->endAction();
		return true;

	}
}