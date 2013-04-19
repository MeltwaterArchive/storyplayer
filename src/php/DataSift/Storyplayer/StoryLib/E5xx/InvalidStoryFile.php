<?php

namespace DataSift\Storyplayer\StoryLib;

use DataSift\Stone\ExceptionsLib\Exxx_Exception;

class E5xx_InvalidStoryFile extends Exxx_Exception
{
    public function __construct($msg)
    {
        parent::__construct(500, $msg, $msg);
    }
}