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

namespace DataSift\Storyplayer\Prose;

/**
 * wrappers around the official Amazon EC2 SDK
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class ExpectsEc2Image extends Ec2ImageBase
{
	public function isAvailable()
	{
		$this->requiresValidImage(__METHOD__);

		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure EC2 image '{$this->amiId}' is available");

		// get the state of the image
		$imageState = $this->image['State'];

		if ($imageState != 'available') {
			$log->endAction("image state is '{$imageState}'");
			throw new E5xx_ExpectFailed(__METHOD__, "state is 'available'", "state is '{$imageState}'");
		}

		// if we get here, all is well
		$log->endAction();
	}

	public function hasFailed()
	{
		$this->requiresValidImage(__METHOD__);

		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure EC2 image '{$this->amiId}' has failed");

		// get the state of the image
		$imageState = $this->image['State'];

		if ($imageState != 'failed') {
			$log->endAction("image state is '{$imageState}'");
			throw new E5xx_ExpectFailed(__METHOD__, "state is 'failed'", "state is '{$imageState}'");
		}

		// if we get here, all is well
		$log->endAction();
	}

	public function isPending()
	{
		$this->requiresValidImage(__METHOD__);

		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure EC2 image '{$this->amiId}' is pending");

		// get the state of the image
		$imageState = $this->image['State'];

		if ($imageState != 'pending') {
			$log->endAction("image state is '{$imageState}'");
			throw new E5xx_ExpectFailed(__METHOD__, "state is 'pending'", "state is '{$imageState}'");
		}

		// if we get here, all is well
		$log->endAction();
	}
}