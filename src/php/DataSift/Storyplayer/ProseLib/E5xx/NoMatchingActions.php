<?php

namespace DataSift\Storyplayer\ProseLib;

class E5xx_NoMatchingActions extends E5xx_StoryTellerException
{
	public function __construct($methodName) {
		$msg = "Cannot find a suitable class for actions of type '$methodName'";
		parent::__construct(500, $msg, $msg);
	}
}