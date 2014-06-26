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
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\PlayerLib;

use stdClass;

/**
 * Helper for loading a list of stories to run, and verifying that
 * it is a list we are happy with
 *
 * @category  Libraries
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class TaleLoader
{
	/**
	 * singleton - do not instantiate
	 * @codeCoverageIgnore
	 */
	protected function __construct()
	{
		// do nothing
	}

	/**
	 * load a story, throwing exceptions if problems are detected
	 *
	 * @param  string $filename
	 *         path to the PHP file containing the story
	 * @return Story
	 *         the story object
	 */
	static public function loadTale($filename)
	{
		if (!file_exists($filename)) {
			throw new E5xx_InvalidStoryListFile("Cannot find file '{$filename}' to load");
		}

		// load the contents
		$contents = file_get_contents($filename);

		// does it decode?
		$tale = json_decode($contents);
		if (!$tale) {
			throw new E4xx_InvalidStoryListFile("Story list '{$filename}' does not contain valid JSON");
		}

		// does it have the elements we require?
		if (!isset($tale->stories)) {
			throw new E4xx_InvalidStoryListFile("Story list '{$filename}' does not contain a 'stories' element");
		}
		if (!is_array($tale->stories)) {
			throw new E4xx_InvalidStoryListFile("The 'stories' element in the story list '{$filename}' must be an array");
		}
		if (count($tale->stories) == 0) {
			throw new E4xx_InvalidStoryListFile("The 'stories' element in the story list '{$filename}' cannot be an empty array");
		}

		// do all of the stories in the list exist?
		foreach ($tale->stories as $index => $storyFile) {
			if (!file_exists($storyFile)) {
				if (!file_exists($filename . DIR_SEPARATOR . $storyFile)) {
					throw new E4xx_InvalidStoryListFile("Cannot find the story file '{$storyFile}' on disk");
				}
				else {
					$tale->stories[$index] = $filename . DIR_SEPARATOR . $storyFile;
				}
			}
		}

		// inject defaults for optional fields
		if (!isset($tale->options)) {
			$tale->options = new stdClass();
		}
		if (!isset($tale->options->reuseTestEnvironment)) {
			$tale->options->reuseTestEnvironment = false;
		}

		// all done
		return $tale;
	}
}