<?php

/**
 * Copyright (c) 2011-present Mediasift Ltd
 * Copyright (c) 2015-present Ganbaro Digital Ltd
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
 * @package   Storyplayer/TestEnvironments
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\TestEnvironments;

/**
 * group adapter for hosts managed by vagrant
 *
 * @category  Libraries
 * @package   Storyplayer/TestEnvironments
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

class Vagrant_GroupAdapter implements GroupAdapter
{
	public function __construct()
	{
		$this->determineBaseFolder();
	}

	// ==================================================================
	//
	// Base folder support goes here
	//
	// ------------------------------------------------------------------

	protected $baseFolder;

	/**
	 * automagically work out where our test environment's files and
	 * such like are
	 *
	 * @return void
	 */
	protected function determineBaseFolder()
	{
		// where should we be looking?
		//
		// first match wins!
		$candidates = [
			dirname(debug_backtrace()[1]['file']),
			getcwd()
		];

		foreach ($candidates as $folder) {
			if (file_exists($folder . '/Vagrantfile')) {
				$this->baseFolder = str_replace(getcwd(), '.', $folder);

				// all done
				return;
			}
		}

		// if we get here, then we do not know where the Vagrantfile
		// is, and it is time to bail
		throw new Vagrant_E4xx_NoVagrantFile($candidates);
	}

    /**
     * which folder should SPv2 be in when interacting with this group
     * of virtual machines?
     *
     * @return string
     */
    public function getBaseFolder()
    {
    	return $this->baseFolder;
    }

	// ==================================================================
	//
	// Host support goes here
	//
	// ------------------------------------------------------------------

	/**
	 * how do we validate any host adapters used by hosts in this group?
	 *
	 * @return HostAdapterValidator
	 */
	public function getHostAdapterValidator()
	{
		return new Vagrant_HostAdapterValidator($this);
	}

	// ==================================================================
	//
	// Stuff to support SPv2.0-style internals goes here
	//
	// Everything below here is technical debt, and the plan is to
	// gradually phase it all out over several SPv2 releases
	//
	// ------------------------------------------------------------------

    /**
     * what type of group are we?
     *
     * this is the name of the class (without namespace) that our group
     * adapter uses
     *
     * @return string
     */
	public function getType()
	{
		return "LocalVagrantVms";
	}
}