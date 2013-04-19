<?php

namespace DataSift\Storyplayer\ProseLib;

use Exception;

class TargettedBase
{
	const SINGLE_TARGET = 1;
	const PLURAL_TARGET = 2;

	protected $tagTypes = array(
		'button'        => 'input',
		'buttons'       => 'input',
		'cell'          => 'td',
		'cells'         => 'td',
		'link'          => 'a',
		'links'         => 'a',
		'orderedlist'   => 'ol',
		'unorderedlist' => 'ul'
	);

	protected $targetTypes = array(
		'box'           => self::SINGLE_TARGET,
		'boxes'         => self::PLURAL_TARGET,
		'button'        => self::SINGLE_TARGET,
		'buttons'       => self::PLURAL_TARGET,
		'cell'          => self::SINGLE_TARGET,
		'cells'         => self::PLURAL_TARGET,
		'dropdown'      => self::SINGLE_TARGET,
		'dropdowns'     => self::PLURAL_TARGET,
		'element'       => self::SINGLE_TARGET,
		'elements'      => self::PLURAL_TARGET,
		'field'         => self::SINGLE_TARGET,
		'fields'        => self::PLURAL_TARGET,
		'link'          => self::SINGLE_TARGET,
		'links'         => self::PLURAL_TARGET,
		'orderedlist'   => self::SINGLE_TARGET,
		'span'          => self::SINGLE_TARGET,
		'unorderedlist' => self::SINGLE_TARGET
	);

	protected $searchTypes = array (
		'id'            => 'ById',
		'label'         => 'ByLabel',
		'labelled'      => 'ByLabel',
		'named'         => 'ByName',
		'name'			=> 'ByName',
		'text'          => 'ByText',
		'class'         => 'ByClass',
		'placeholder'   => 'ByPlaceholder',
		'title'			=> 'ByTitle',
		'labelidortext' => 'ByLabelIdText',
	);

	protected function convertMethodNameToWords($methodName)
	{
		// turn the method name into an array of words
		$words = explode(' ', strtolower(preg_replace('/([^A-Z])([A-Z])/', "$1 $2", $methodName)));

		// all done
		return $words;
	}

	protected function determineSearchType($words)
	{
		foreach ($words as $word) {
			if (isset($this->searchTypes[$word])) {
				return $this->searchTypes[$word];
			}
		}

		// if we do not recognise the word, tell the caller
		return null;
	}

	protected function determineTargetType($words)
	{
		foreach ($words as $word) {
			if (isset($this->targetTypes[$word])) {
				return $word;
			}
		}

		// if we do not recognise the word, substitute a suitable default
		return 'field';
	}

	protected function determineTagType($targetType)
	{
		// do we have a specific tag to look for?
		if (isset($this->tagTypes[$targetType])) {
			return $this->tagTypes[$targetType];
		}

		// no, so return the default to feed into xpath
		return '*';
	}

	protected function isPluralTarget($targetType)
	{
		// is this a valid target type?
		if (!isset($this->targetTypes[$targetType])) {
			throw new Exception("Unknown target type '$targetType'");
		}

		// is this a plural target?
		if ($this->targetTypes[$targetType] == self::PLURAL_TARGET) {
			return true;
		}

		// no, it is not
		return false;
	}
}