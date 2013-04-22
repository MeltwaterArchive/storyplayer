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
 * @category    Libraries
 * @package     Storyplayer
 * @subpackage  Prose
 * @author      Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright   2011-present Mediasift Ltd www.datasift.com
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\StoryLib;

use PHPUnit_Framework_TestCase;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

class StoryContextTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers DataSift\Storyplayer\StoryLib\StoryContext::__construct
	 */
	public function testCanInstantiate()
	{
	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new StoryContext();

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($obj instanceof StoryContext);
	}

	/**
	 * @covers DataSift\Storyplayer\StoryLib\StoryContext::__construct
	 * @covers DataSift\Storyplayer\StoryLib\StoryContext::getHostIpAddress
	 */
	public function testGetsHostIpAddressWhenInstantiated()
	{
	    // ----------------------------------------------------------------
	    // perform the change

	    $obj = new StoryContext();

	    // ----------------------------------------------------------------
	    // test the results
		//
		// we can't compare against the host's actual IP address (because
		// that is non-trivial to work out in this test), but we can
		// make sure that we have something, and that it is a valid
		// IP address

	    $this->assertTrue(isset($obj->env));
	    $this->assertTrue(isset($obj->env->host));
	    $this->assertTrue(isset($obj->env->host->ipAddress));

	    // make sure we have a well-formatted IP address
	    $parts = explode('.', $obj->env->host->ipAddress);
	    $this->assertEquals(4, count($parts));
	    $this->assertTrue((int)$parts[0]>= 1);
	    $this->assertTrue((int)$parts[0]<= 255);
	    $this->assertTrue((int)$parts[1]>= 0);
	    $this->assertTrue((int)$parts[1]<= 255);
	    $this->assertTrue((int)$parts[2]>= 0);
	    $this->assertTrue((int)$parts[2]<= 255);
	    $this->assertTrue((int)$parts[3]>= 0);
	    $this->assertTrue((int)$parts[3]<= 255);

	    // make sure we don't have localhost
	    $this->assertNotEquals("127.0.0.1", $obj->env->host->ipAddress);
		$this->assertNotEquals("127.0.1.1", $obj->env->host->ipAddress);
	}

}
