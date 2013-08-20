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
 * @author    Michael Heap <michael.heap@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Cli;

use Phix_Project\CliEngine;
use Phix_Project\CliEngine\CliCommand;
use Phix_Project\CliEngine\CliEngineSwitch;
use Phix_Project\CliEngine\CliResult;

use DataSift\Stone\DownloadLib\FileDownloader;

use DataSift\WebDriver\WebDriverConfiguration;

use Exception;
use stdClass;

/**
 * Command to download dependencies
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Michael Heap <michael.heap@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class InstallCommand extends CliCommand
{
	public function __construct()
	{
		// define the command
		$this->setName('install');
		$this->setShortDescription('download optional dependencies');
		$this->setLongDescription(
			"Use this command to download any optional dependencies"
			. " that you might need e.g. chromedriver for selenium."
			.PHP_EOL
		);
	}

	public function processCommand(CliEngine $engine, $params = array(), $additionalContext = null)
	{
		// tell the user what is happening
		echo "Additional files will be added to the vendor/ folder\n";

		// find a list of files that we need to download
		$wdConfig = new WebDriverConfiguration;
		$filesToDownload = $wdConfig->getDependencies();

		// add in the Sauce Connect JAR
		$sauceConnect = new stdClass;
		$sauceConnect->name = "Sauce-Connect.jar";
		$sauceConnect->url  = "http://saucelabs.com/downloads/Sauce-Connect-latest.zip";
		$filesToDownload[] = $sauceConnect;

		// helper for downloading files
		$downloader = new FileDownloader();

		// let's get the files downloaded
		foreach ($filesToDownload as $file){

			if (!is_object($file->url)){
				$url = $file->url;
			} else {
				$platform = strtolower(php_uname("s") . '/' . php_uname("m"));
				if (isset($file->url->{$platform})){
					$url = $file->url->{$platform};
				} else if (isset($file->url->generic)){
					$url =  $file->url->generic;
				}
			}

			if (!isset($url)){
				throw new Exception("No supported downloads for ".$file->name);
			}

			// How big is the file?
			// via http://www.php.net/manual/en/function.filesize.php#84130
			$headers = array_change_key_case(get_headers($url, 1),CASE_LOWER);
			if ( !preg_match('/HTTP\/1\.(0|1) 200 OK/', $headers[0] ) ) {
				$fileSize = $headers['content-length'][1];
			} else {
				$fileSize = $headers['content-length'];
			}

			// Update the user on what's going on
			echo "Downloading: " . $url.' ('.round($fileSize/1024/1024, 3).'mb)'.PHP_EOL;

			// Download it
			$fileBase = basename($url);
			$downloader->download($url, "./vendor/bin/".$fileBase);

			// Make sure that the relevant files are executable
			if (isset($file->makeExecutable)) {
				foreach ($file->makeExecutable as $exec){
					chmod("./vendor/bin/".$exec, 0755);
				}
			}
		}
	}
}
