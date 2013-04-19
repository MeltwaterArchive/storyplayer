<?php

namespace DataSift\Storyplayer\StoryLib;

use DataSift\Stone\ExceptionsLib\Exxx_Exception;

class E5xx_NoStoryActions extends Exxx_Exception
{
    public function __construct($storyName)
    {
    	$msg = "No actions for story '$storyName'";
        parent::__construct(500, $msg, $msg);
    }
}