<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;
use DataSift\Storyplayer\ProseLib\E5xx_ExpectFailed;
use DataSift\Stone\ExceptionsLib\E5xx_NotImplemented;
use DataSift\Stone\LogLib\Log;

class UserExpects extends ProseActions
{
	// ====================================================================
	//
	// Common preflight checks
	//
	// --------------------------------------------------------------------

	public function isValidForStory()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure the user valid for this story");

		// get the story that we're playing
		$story = $st->getStory();

		// get the user to examine
		$user = $st->getUser();

		// we need to check the user that has been picked against the
		// story that is to be told
		foreach ($user->roles as $role)
		{
			if ($story->hasRole($role))
			{
				$log->endAction("yes - has role '{$role}'");
				return true;
			}
		}

		// if we get there, then there are no matches
		throw new E5xx_ActionFailed('UserExpects::isValidForStory');
	}

	// ====================================================================
	//
	// Tests for different kinds of user
	//
	// --------------------------------------------------------------------

	public function isAdminUser()
	{
		throw new E5xx_NotImplemented(__METHOD__);

	}

	public function isCustomUser()
	{
		throw new E5xx_NotImplemented(__METHOD__);

	}

	public function isFinanceUser()
	{
		throw new E5xx_NotImplemented(__METHOD__);

	}

	public function isFreeUser()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure the user a 'free' user");

		// we expect the user to have the 'top up' button
		$st->usingDashboard()->gotoDashboardPage();
		$st->expectsCurrentPage()->hasTopupButton();

		// we expect the user to have visible credit
		$st->expectsCurrentPage()->hasCreditShowing();

		// we expect the user to be on the 'free' billing plan
		$st->expectsBilling()->onFreePlan();

		// we expect the user to have no invoices showing in
		// the billing section
		$st->expectsBilling()->hasNoInvoices();

		// all done
		$log->endAction();
	}

	public function isInternalUser()
	{
		throw new E5xx_NotImplemented(__METHOD__);

	}

	public function isPaygUser()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure the user is a 'payg' user");

		// we expect the user to be on the 'payg' billing plan
		$st->expectsBilling()->onPaygPlan();

		// we expect the user to have the 'top up' button
		$st->usingDashboard()->gotoDashboardPage();
		$st->expectsCurrentPage()->hasTopupButton();

		// we expect the user to have visible credit
		$st->expectsCurrentPage()->hasCreditShowing();

		// we expect the user to have at least one invoice showing in
		// the billing section
		$st->expectsBilling()->hasInvoices();

		// all done
		$log->endAction();
	}

	public function isSubscriptionUser($planname)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make usre the user is on the '{$planname}' subscription plan");

		// we expect the user to be on the 'subscription' billing plan
		$st->expectsBilling()->onSubscriptionPlan($planname);

		// we expect the user to have the DPU battery
		$st->usingDashboard()->gotoDashboardPage();
		$st->expectsCurrentPage()->hasDpuBattery();

		// all done
		$log->endAction();
	}

	// ====================================================================
	//
	// Account-related checks
	//
	// --------------------------------------------------------------------

	public function isLoggedIn()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure the user is logged in");

		if (!$st->fromLogin()->isLoggedIn()) {
			throw new E5xx_ExpectFailed(__METHOD__, "logout link present", "logout link not found");
		}

		// all done
		$log->endAction();
	}

	public function companyNameIs($expected)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("make sure the user's company name is '{$expected}'");

		// what is the current set company name?
		$actual = $st->fromUser()->getCompanyName();

		// do they match?
		if ($expected != $actual) {
			throw new E5xx_ExpectFailed(__METHOD__, $expected, $actual);
		}

		// all done
		$log->endUser();
	}
}