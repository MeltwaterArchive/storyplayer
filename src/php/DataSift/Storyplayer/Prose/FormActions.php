<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\PageContext;
use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;
use DataSift\Storyplayer\ProseLib\TargettedAction;
use DataSift\Storyplayer\ProseLib\TargettedSearch;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

use DataSift\Stone\ExceptionsLib\E5xx_NotImplemented;
use DataSift\Stone\LogLib\Log;

class FormActions extends BrowserActions
{
	protected $formId = null;
	protected $formElement = null;

	protected function initActions()
	{
		// shorthand
		$st     = $this->st;
		$formId = $this->args[0];

		// find the form
		$formElement = $st->fromCurrentPage()->getElementById($formId);

		// is it really a form?
		if ($formElement->name() !== 'form') {
			throw new E5xx_ActionFailed('form');
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
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("clear " . count($fields) . " field(s) in the form '{$this->formId}'");

		foreach ($fields as $labelText => $fieldValue) {
			$this->st->usingForm($this->formId)->clear()->theFieldLabelled($labelText);
		}

		$log->endAction();
	}

	public function fillInFields($fields)
	{
		// shorthand
		$st     = $this->st;
		$formId = $this->formId;

		// what are we doing?
		$log = $st->startAction("fill in " . count($fields) . " field(s) in the form '{$this->formId}'");

		foreach ($fields as $labelText => $fieldValue) {
			// find the element
			$element = $st->fromForm($formId)->getElementByLabelIdOrName($labelText);
			$tag     = $element->name();

			switch ($tag) {
				case 'input':
				case 'textarea':
					$this->type($fieldValue)->intoElement($element, $labelText);
					break;

				case 'select':
					$this->select($fieldValue)->inElement($element, $labelText);
					break;

				case null:
					$log->endAction("cannot find field labelled '{$labelText}'");
					throw new E5xx_ActionFailed(__METHOD__);
					break;

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
		$st     = $this->st;
		$formId = $this->formId;

		// what are we doing?
		$log = $st->startAction("fill in " . count($fields) . " field(s) in form '{$formId}' if present");

		foreach ($fields as $labelText => $fieldValue) {
			// find the element
			$element = $log->addStep("( finding field with label, id or name '{$labelText}'", function($log) use($st, $formId, $labelText) {
				try {
					return $st->fromForm($formId)->getElementByLabelIdOrName($labelText);
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

			$tag = $element->name();
			switch ($tag) {
				case 'input':
				case 'textarea':
					$this->type($fieldValue)->intoElement($element, $labelText);
					break;

				case 'select':
					$this->select($fieldValue)->inElement($element, $labelText);
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