<?php

namespace DataSift\Storyplayer\PlayerLib;

use DataSift\Stone\ExceptionsLib\Exxx_Exception;

class E5xx_CheckpointIsReadOnly extends Exxx_Exception
{
	public function __construct() {
		$msg = "Cannot change data in the checkpoint; checkpoint is currently in readonly mode";
		parent::__construct(500, $msg, $msg);
	}
}