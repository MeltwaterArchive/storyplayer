<?php

namespace DataSift\Storyplayer\ProseLib;

use DataSift\Stone\ExceptionsLib\E5xx_NotImplemented;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

class AssertActions extends ProseActions
{
	protected $comparitor = null;

	public function __construct(StoryTeller $st, $comparitor)
	{
		$this->comparitor = $comparitor;
		parent::__construct($st);
	}

	public function __call($methodName, $params)
	{
		// pass this through to our comparitor, if it has the same method
		// name
		if (method_exists($this->comparitor, $methodName)) {
			$result = call_user_func_array(array($this->comparitor, $methodName), $params);

			// was the comparison successful?
			if ($result->hasPassed()) {
				return true;
			}

			// if we get here, then the comparison failed
			throw new E5xx_ExpectFailed(__CLASS__ . "::${methodName}", $result->getExpected(), $result->getActual());
		}

		// this only gets called if there's no matching method
		throw new E5xx_NotImplemented(get_class($this) . '::' . $methodName);
	}
}
