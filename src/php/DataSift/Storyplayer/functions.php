<?php

use DataSift\Storyplayer\StoryLib\Story;
use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;

/**
 * Create a new story object
 *
 * @param  string $category the category that the story belongs to
 * @return Story            the new story object to use
 */
function newStoryFor($category)
{
	$story = new Story();
	$story->setCategory($category);

	return $story;
}

/**
 * Attempt an action, and if it fails, swallow the failure
 *
 * @param  callback $callback the action(s) to attempt
 * @return void
 */
function tryTo($callback) {
	try {
		$callback();
	}
	catch (E5xx_ActionFailed $e) {
		// do nothing
	}
}
