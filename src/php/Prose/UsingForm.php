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

namespace Prose;

use Exception;

/**
 * do things to forms in the web browser
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class UsingForm extends UsingBrowser
{
	protected $formId = null;
	protected $formElement = null;

	protected function initActions()
	{
		// call our parent initActions() first
		parent::initActions();

		// shorthand
		$formId = $this->args[0];

		// find the form
		$formElement = fromBrowser()->get()->elementById($formId);

		// is it really a form?
		if (strtolower($formElement->name()) !== 'form') {
			throw new E5xx_ActionFailed(__METHOD__, "expected form element, got element '" . $formElement->name() . "'");
		}

		// yes, it really is a form
		$this->formId      = $formId;
		$this->setTopElement($formElement);
	}

	// ==================================================================
	//
	// Mass form-filling goes here
	//
	// ------------------------------------------------------------------

	public function clearFields($fields)
	{
		// what are we doing?
		$log = usingLog()->startAction("clear " . count($fields) . " field(s) in the form '{$this->formId}'");

		foreach ($fields as $labelText => $fieldValue) {
			usingForm($this->formId)->clear()->theFieldLabelled($labelText);
		}

		$log->endAction();
	}

	public function fillInFields($fields)
	{
		// shorthand
		$formId = $this->formId;

		// what are we doing?
		$log = usingLog()->startAction("fill in " . count($fields) . " field(s) in the form '{$this->formId}'");

		foreach ($fields as $labelText => $fieldValue) {
			// find the element
			$element = fromForm($formId)->getElementByLabelIdOrName($labelText);
			$tag     = $element->name();

			switch ($tag) {
				case 'input':
				case 'textarea':
					$this->type($fieldValue)->intoElement($element);
					break;

				case 'select':
					$this->select($fieldValue)->fromElement($element);
					break;

				case null:
					$log->endAction("cannot find field labelled '{$labelText}'");
					throw new E5xx_ActionFailed(__METHOD__);

				default:
					$log->endAction("* field labelled '{$labelText}' has unsupported tag '{$tag}' *");
					throw new E5xx_ActionFailed(__METHOD__);
			}
		}

		// all done
		$log->endAction();
	}

	public function fillInFieldsIfPresent($fields)
	{
		// shorthand
		$formId = $this->formId;

		// what are we doing?
		$log = usingLog()->startAction("fill in " . count($fields) . " field(s) in form '{$formId}' if present");

		foreach ($fields as $labelText => $fieldValue) {
			// find the element
			$element = $log->addStep("finding field with label, id or name '{$labelText}'", function($log) use($formId, $labelText) {
				try {
					return fromForm($formId)->getElementByLabelIdOrName($labelText);
				}
				catch (Exception $e) {
					$log->endAction("field '{$labelText}' not present; ignoring!");
					return null;
				}
			});

			// did we get one?
			if ($element == null) {
				// missing field
				continue;
			}

			$tag = strtolower($element->name());
			switch ($tag) {
				case 'input':
				case 'textarea':
					$this->type($fieldValue)->intoElement($element);
					break;

				case 'select':
					$this->select($fieldValue)->fromElement($element);
					break;

				default:
					$log->endAction("* field '{$labelText}' has unexpected tag '{$tag}' *");
					throw new E5xx_ActionFailed(__METHOD__);
			}
		}

		// all done
		$log->endAction();
	}
}