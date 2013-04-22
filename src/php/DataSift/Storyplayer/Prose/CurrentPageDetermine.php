<?php

namespace DataSift\Storyplayer\Prose;

use Exception;

use DataSift\Storyplayer\ProseLib\ProseActions;

use DataSift\Stone\ExceptionsLib\E5xx_NotImplemented;
use DataSift\Stone\LogLib\Log;

class CurrentPageDetermine extends BrowserDetermine
{
	public function getCurrentCreditBalance()
	{
		// shorthand
		$st      = $this->st;
		$browser = $this->getTopElement();

		// what are we doing?
		$log = $st->startAction("get the user's current credit balance");

		try {
			$amount = $browser->element('id', 'current_balance')->text();

			// strip off any thousands place holders
			$amount = str_replace(',', '', $amount);

			// all done
			$log->endAction("current balance is '{$amount}'");
			return $amount;
		}
		catch (Exception $e) {
			$log->endAction("information is not available");
			return null;
		}
	}

	public function hasCreateStream()
	{
		// shorthand
		$st      = $this->st;
		$browser = $this->getTopElement();

		// what are we doing?
		$log = $st->startAction("check if 'Create Stream' is on the page");

		try {
			$element = $browser->element('xpath', 'descendant::a[@href="/streams/create"]');
			$log->endAction("it is");
			return true;
		}
		catch (Exception $e) {
			$log->endAction("it is not");
			return false;
		}
	}

	public function hasDpuBattery()
	{
		// shorthand
		$st      = $this->st;
		$browser = $this->getTopElement();

		// what are we doing?
		$log = $st->startAction("check if the DPU battery is on the page");

		try {
			$element = $browser->element('xpath', 'descendant::div[@class="balance-dpus"]');
			$log->endAction("it is");
			return true;
		}
		catch (Exception $e) {
			$log->endAction("it is not");
			return false;
		}
	}

	public function hasLogoutLink()
	{
		// shorthand
		$st      = $this->st;
		$browser = $this->getTopElement();

		// what are we doing?
		$log = $st->startAction("check current page for a 'Logout' link");

		try {
			$element = $browser->element('id', 'logout_link');
			$log->endAction("found 'Logout' link");
			return true;
		}
		catch (Exception $e) {
			$log->endAction("did not find 'Logout' link");
			return false;
		}
	}

	public function hasNextPage()
	{
		// shorthand
		$st      = $this->st;
		$browser = $this->getTopElement();

		// what are we doing?
		$log = $st->startAction("check current page for a 'Next Page' link");

		try {
			$element = $browser->element('xpath', 'descendant::a[@title="Page Next"]');
			$log->endAction("found 'Next Page' link");
			return true;
		}
		catch (Exception $e) {
			$log->endAction("did not find 'Page Next' link");
			return false;
		}
	}

	public function hasTopupButton()
	{
		// shorthand
		$st      = $this->st;
		$browser = $this->getTopElement();

		// what are we doing?
		$log = $st->startAction("check current page for the 'Topup' button");

		try {
			$element = $browser->element('id', 'top_up_button');
			$log->endAction("found 'Topup' button");
			return true;
		}
		catch (Exception $e) {
			$log->endAction("did not find 'Topup' button");
			return false;
		}
	}

	public function getRemainingDpuAllowance()
	{
		// shorthand
		$st      = $this->st;
		$browser = $this->getTopElement();

		// what are we doing?
		$log = $st->startAction("get the DPU allowance from the current page");

		try {
			$element = $browser->element('xpath', 'descendant::div[@class="balance-dpus"]/a/strong');
			$rawText = $element->text();
			$amount = str_replace(array(',', ' DPU'), array(), $rawText);

			$log->endAction("DPU allowance is '{$amount}'");
			return $amount;
		}
		catch (Exception $e) {
			Log::write(Log::LOG_DEBUG, $e->getMessage());

			$log->endAction("DPU allowance is not visible on the page");
			return null;
		}
	}

	public function hasFooterPresent()
	{
		// shorthand
		$st      = $this->st;
		$browser = $this->getTopElement();

		// what are we doing?
		$log = $st->startAction("check current page for presence of the footer");

		$element = $browser->element('id', 'footer');
		if ($element) {
			$log->endAction("found the footer");
			return true;
		}

		$log->endAction("did not find the footer");
		return false;
	}

	public function hasErrorFlashMessages()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("check for error flash messages");

		if ($st->fromCurrentPage()->has()->fieldWithClass('controls-alert error')) {
			$log->endAction("found error flash message(s)");
			return true;
		}

		// all done
		$log->endAction("did not find error flash message(s)");
		return false;
	}

	public function getSuccessFlashMessages()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("get all success-type flash messages");

		// find them
		$elements = $st->fromCurrentPage()->get()->fieldsWithClass("controls-alert ok");

		// convert them into text
		$return = array();
		foreach ($elements as $element) {
			$return[] = $element->text();
		}

		// all done
		$log->endAction("found " . count($return) . " messages");
		return $return;
	}
}