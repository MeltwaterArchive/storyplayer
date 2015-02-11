<?php

$story = newStoryFor("Storyplayer")
      ->inGroup("Web Pages")
      ->called("Can Get Title Of A Web Page");

$story->requiresStoryplayerVersion(2);

$story->addAction(function() {
	$checkpoint = getCheckpoint();

	usingBrowser()->gotoPage("http://php.net");
	$checkpoint->title = fromBrowser()->getTitle();
});

$story->addPostTestInspection(function() {
	$checkpoint = getCheckpoint();

	assertsObject($checkpoint)->hasAttribute("title");
	assertsString($checkpoint->title)->equals("PHP: Hypertext Preprocessor");
});
