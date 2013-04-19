<?php

namespace DataSift\Storyplayer\StoryLib;

class StoryLoader
{
	static public function loadStory($filename)
	{
		if (!file_exists($filename)) {
			throw new E5xx_InvalidStoryFile("Cannot find file '$filename' to load");
		}

		// load the story
		include($filename);

		// there should now be a $story in scope
		if (!isset($story)) {
			throw new E5xx_InvalidStoryFile("Story file did not create the \$story variable");
		}

		// make sure we have the right story
		if (!$story instanceof Story) {
			throw new E5xx_InvalidStoryFile("Story file did create a \$story variable of the expected type");
		}

		// all done
		return $story;
	}
}