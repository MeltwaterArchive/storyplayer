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
 * @package   Storyplayer/StoryLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\StoryLib;

/**
 * Helper for loading a single story, and verifying that the story was
 * properly created after being loaded
 *
 * @category  Libraries
 * @package   Storyplayer/StoryLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class StoryLoader
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
	static public function loadStory($filename)
	{
		if (!file_exists($filename)) {
			throw new E5xx_InvalidStoryFile("Cannot find file '{$filename}' to load");
		}

		// load the story
		include($filename);

		// there should now be a $story in scope
		if (!isset($story)) {
			throw new E5xx_InvalidStoryFile("Story file '{$filename}' did not create the \$story variable");
		}

		// make sure we have the right story
		if (!$story instanceof Story) {
			throw new E5xx_InvalidStoryFile("Story file '{$filename}' did create a \$story variable, but it is of type '" . get_class($story) . "' instead of type 'DataSift\Storyplayer\StoryLib\Story'");
		}

		// all done
		return $story;
	}
}