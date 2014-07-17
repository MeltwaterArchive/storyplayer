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
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Cli;

use DataSift\Stone\ObjectLib\BaseObject;

/**
 * support for working with the list of known systems-under-test
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
trait Injectables_KnownSystemsUnderTestSupport
{
	public $knownSystemsUnderTest;
	public $knownSystemsUnderTestList = array();

	public function initKnownSystemsUnderTestSupport($additionalSuts)
	{
		// special case:
		//
		// did we find any systems-under-test in the config files?
		//
		// if we didn't, then we create a fake system-under-test entry
		// so that Storyplayer will run. this is better for new users
		// than Storyplayer throwing an error because they haven't created
		// any systems-under-test in the configs
		if (count($additionalSuts) == 0) {
			$this->knownSystemsUnderTest = new KnownSystemsUnderTest;

			foreach ($this->knownSystemsUnderTest as $name => $config) {
				$this->knownTestEnvironmentsList[$name] = $name;
			}

			// all done
			return;
		}

		// if we get here, then we ONLY want the systems-under-test that
		// have been explicitly defined in the configs
		//
		// the hardcoded systems-under-test in the KnownSystemsUnderTest
		// class are only a fallback for when there is NO config at all
		//
		// we start with NO known systems under test
		$this->knownSystemsUnderTest = new BaseObject;

		// now add in all the systems-under-test that we have discovered
		// in the config files
		foreach ($additionalSuts as $filename => $config) {
			$sutName = basename($filename, 'json');
			$this->knownSystemsUnderTestList[$sutName] = $sutName;
			$this->knownSystemsUnderTest->$sutName     = $config;
		}

		// now put the list of systems-under-test into a sensible order
		ksort($this->knownSystemsUnderTestList);

		// all done
	}
}
