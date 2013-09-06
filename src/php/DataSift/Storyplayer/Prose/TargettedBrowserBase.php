<?php

/**
 * Copyright (c) 2011-present Mediasift Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Prose;

/**
 * Base class for all the TargettedBrowser* helper classes
 *
 * The main thing this base class offers is the process for converting a
 * faked element type (such as 'buttonLabelled') into something specific
 * that we can go and find
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class TargettedBrowserBase
{
	const SINGLE_TARGET = 1;
	const PLURAL_TARGET = 2;

	protected $tagTypes = array(
		'button'        => array('input', 'button'),
		'buttons'       => array('input','button'),
		'cell'          => 'td',
		'cells'         => 'td',
		'heading'		=> array('h1', 'h2', 'h3', 'h4', 'h5','h6'),
		'headings'		=> array('h1', 'h2', 'h3', 'h4', 'h5','h6'),
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
		'heading'		=> self::SINGLE_TARGET,
		'headings'		=> self::PLURAL_TARGET,
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
			throw new E5xx_UnknownDomElementType($targetType);
		}

		// is this a plural target?
		if ($this->targetTypes[$targetType] == self::PLURAL_TARGET) {
			return true;
		}

		// no, it is not
		return false;
	}
}