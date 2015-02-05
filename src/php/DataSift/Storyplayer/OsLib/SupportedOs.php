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
 * @package   Storyplayer/OsLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\OsLib;

use stdClass;
use DataSift\Storyplayer\HostLib\SupportedHost;

/**
 * the things you can do / learn about a machine running one of our
 * supported operatating systems
 *
 * @category  Libraries
 * @package   Storyplayer/OsLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
interface SupportedOs
{
	/**
	 *
	 * @param  HostDetails   $hostDetails
	 * @param  SupportedHost $vm
	 * @return string
	 */
	public function determineIpAddress($hostDetails, SupportedHost $vm);

	/**
	 * @param HostDetails $hostDetails
	 * @param string $packageName
	 * @return stdClass
	 */
	public function getInstalledPackageDetails($hostDetails, $packageName);

	/**
	 * @param HostDetails $hostDetails
	 * @param string $processName
	 * @return boolean
	 */
	public function getProcessIsRunning($hostDetails, $processName);

	/**
	 * @param HostDetails $hostDetails
	 * @param string $processName
	 * @return integer
	 */
	public function getPid($hostDetails, $processName);

	/**
	 * @param HostDetails $hostDetails
	 * @param string $command
	 *
	 * @return \DataSift\Storyplayer\CommandLib\CommandResult
	 */
	public function runCommand($hostDetails, $command);

	/**
	 * download a file from a (possibly) remote host to wherever
	 * Storyplayer is running
	 *
	 * @param  HostDetails $hostDetails
	 *         the details of the host to upload to
	 * @param  string $sourceFilename
	 *         path to the file to download
	 * @param  string $destFilename
	 *         path to download the file to
	 * @return void
	 */
	public function downloadFile($hostDetails, $sourceFilename, $destFilename);

	/**
	 * upload a file from wherever Storyplayer is running to the
	 * (possibly) remote host
	 *
	 * @param  HostDetails $hostDetails
	 *         the details of the host to upload to
	 * @param  string $sourceFilename
	 *         path to the file to upload
	 * @param  string $destFilename
	 *         path to upload the file to
	 * @return void
	 */
	public function uploadFile($hostDetails, $sourceFilename, $destFilename);

	/**
	 * get details about a filesystem entry
	 *
	 * @param  HostDetails $hostDetails
	 *         the details of the host to query
	 * @param  string $filename
	 *         path to the file/folder to query
	 * @return stdClass
	 */
	public function getFileDetails($hostDetails, $filename);
}