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

namespace Storyplayer\SPv3\TestEnvironments;

use DataSift\Stone\ObjectLib\BaseObject;

/**
 * support for building a test environment using dsbuild
 *
 * @category  Libraries
 * @package   Storyplayer/TestEnvironments
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

class Dsbuild_Adapter implements ProvisioningAdapter
{
	public function __construct()
	{
		$this->setExecutePath(getcwd() . DIRECTORY_SEPARATOR . "dsbuild.sh");
	}

	// ==================================================================
	//
	// Support for executing the script
	//
	// ------------------------------------------------------------------

	/**
	 * where is the script that we are going to execute?
	 *
	 * @var string
	 */
	protected $executePath;

	/**
	 * which folder are we executing things in?
	 *
	 * @return string
	 */
	public function getExecuteDir()
	{
		return dirname($this->executePath);
	}

	/**
	 * where is the script that we are going to execute?
	 *
	 * @return string
	 */
	public function getExecutePath()
	{
		return $this->executePath;
	}

	/**
	 * tell me which script to execute
	 *
	 * @param string $path
	 *        path to the dsbuild script
	 */
	public function setExecutePath($path)
	{
		$this->executePath = $path;

		return $this;
	}

	// ==================================================================
	//
	// SPv3.0-style config support
	//
	// ------------------------------------------------------------------

	public function getAsConfig()
	{
		// our return value
		$retval = new BaseObject;

		// this is who we are
		$retval->engine = "dsbuild";

		// this is what needs running
		$retval->execute = $this->getExecutePath();

		// all done
		return $retval;
	}
}
