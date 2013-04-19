<?php

namespace DataSift\Storyplayer\Prose;

use Exception;

use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\ProseLib\E5xx_ExpectFailed;

use DataSift\Stone\ExceptionsLib\E5xx_NotImplemented;
use DataSift\Stone\LogLib\Log;

class CurrentPageExpects extends BrowserExpects
{
	public function hasCreateNewFilter()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("'Create New Filter' link must be on the page");

		// find it
		if (!$this->st->fromCurrentPage()->hasCreateNewFilter()) {
			throw new E5xx_ExpectFailed(__METHOD__, 'exists', null);
		}

		// all done
		$log->endAction();
	}

	public function hasCreateStream()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("'Create Stream' link must be on the page");

		// find it
		if (!$this->st->fromCurrentPage()->hasCreateStream()) {
			throw new E5xx_ExpectFailed(__METHOD__, 'exists', null);
		}

		// all done
		$log->endAction();
	}

	public function hasCreditShowing()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("the user's credit must be shown on the page");

		// find it
		if ($this->st->fromCurrentPage()->getCurrentCreditBalance() === null) {
			throw new E5xx_ExpectFailed(__METHOD__, 'exists', null);
		}

		// all done
		$log->endAction();
	}

	public function hasDpuBattery()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("the DPU battery must be shown on the page");

		// find it
		if ($this->st->fromCurrentPage()->hasDpuBattery() === null) {
			throw new E5xx_ExpectFailed(__METHOD__, 'exists', null);
		}

		// all done
		$log->endAction();
	}

	public function hasErrorFlashMessages()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("error-type flash message(s) must be shown on the page");

		// find it
		if (!$st->fromCurrentPage()->hasErrorFlashMessages()) {
			throw new E5xx_ExpectFailed(__METHOD__, 'exists', 'does not exist');
		}

		// all done
		$log->endAction();
	}

	public function hasNoErrorFlashMessages()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("there must not be any error-type flash messages");

		// find it
		if ($st->fromCurrentPage()->hasErrorFlashMessages()) {
			throw new E5xx_ExpectFailed(__METHOD__, 'does not exist', 'does exist');
		}

		// all done
		$log->endAction();
	}

	public function hasSuccessFlashMessage($message)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("there must be a success-type flash message '{$message}'");

		// find it
		$messages = $st->fromCurrentPage()->getSuccessFlashMessages();

		// did we get it?
		if (!in_array($message, $messages)) {
			throw new E5xx_ExpectFailed(__METHOD__, "message '{$message}'", "not found");
		}

		// all done
		$log->endAction();
	}

	public function hasNoSuccessFlashMessages()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("there must be no success-type flash messages");

		// find it
		$messages = $st->fromCurrentPage()->getSuccessFlashMessages();

		// did we get it?
		if (count($messages) > 0) {
			throw new E5xx_ExpectFailed(__METHOD__, "no sucess-type flash messages", count($messages) . " message(s) found");
		}

		// all done
		$log->endAction();
	}

	public function selectedTabIs($tabName)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("check that '$tabName' is the selected tab on the page");

		// find it
		$element = $st->fromCurrentPage()->getElementByText($tabName);
		$parent  = $element->element('xpath', '..');

		$classes = explode(' ', $parent->attribute('class'));
		if (!in_array('selected', $classes)) {
			throw new E5xx_ExpectFailed(__METHOD__, 'tab $tabName selected', 'tab $tabName not selected');
		}

		// all done
		$log->endAction();
	}

	public function hasTopupButton()
	{
		Log::write(Log::LOG_DEBUG, "Looking for topup button");
		if (!$this->st->fromCurrentPage()->hasTopupButton()) {
			throw new E5xx_ExpectFailed('CurrentPageExpects::hasTopupButton', 'exists', null);
		}

		Log::write(Log::LOG_DEBUG, "Found topup button");
	}

}