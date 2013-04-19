<?php

namespace DataSift\Storyplayer\PlayerLib;

use DataSift\Stone\ExceptionsLib\Exxx_Exception;

class E5xx_NoSuchDataInCheckpoint extends Exxx_Exception
{
	public function __construct($key) {
		$msg = "No such data '{$key}' in the checkpoint";
		parent::__construct(500, $msg, $msg);
	}
}