<?php

namespace DataSift\Storyplayer\ProseLib;

class E5xx_ActionFailed extends E5xx_StoryTellerException
{
	public function __construct($actionName, $params = array()) {
		$msg = "Action '$actionName' failed";
		parent::__construct(500, $msg, $msg);
	}
}