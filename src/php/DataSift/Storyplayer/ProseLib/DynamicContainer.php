<?php

namespace DataSift\Storyplayer\ProseLib;

use DataSift\Storyplayer\PlayerLib\StoryTeller;

class DynamicContainer
{
	protected $st = null;
	protected $type = null;

	public function __construct($containerType, StoryTeller $st)
	{
		$this->type = $containerType;
		$this->st   = $st;
	}

	/**
	 * Magic to dynamically create the right actions object to return
	 * to the caller.
	 *
	 * Yes, I am probably going to Hell for this ... but just how else
	 * would you solve this problem?
	 *
	 * @param  string $methodName name of the method being called
	 * @param  array  $methodArgs the arguments passed to the method
	 * @return Object
	 */
	public function __call($methodName, $methodArgs)
	{
		// what is the name of the class we're looking for?
		$classSuffix = ucfirst($methodName) . ucfirst($this->type);

		// a list of the namespaces we're going to search for this class
		$namespaces = array (
			"DataSift\\Storyplayer\\Prose"
		);

		// can we find the class?
		foreach ($namespaces as $namespace) {
			$className = $namespace . "\\" . $classSuffix;

			if (class_exists($className)) {
				$return = new $className(
					$this->st,
					$methodArgs);

				return $return;
			}
		}

		// if we get there, then we cannot find a suitable class
		throw new E5xx_NoMatchingActions($methodName);
	}
}