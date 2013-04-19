<?php

namespace DataSift\Storyplayer\ProseLib;

class E5xx_ExpectFailed extends E5xx_StoryTellerException
{
	public function __construct($actionName, $expected, $found) {
		$msg = "Action '$actionName' failed; expected '$expected', found '$found'";
		parent::__construct(500, $msg, $msg);
	}
}