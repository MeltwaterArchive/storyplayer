<?php

namespace DataSift\Storyplayer\Prose;

use Exception;
use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

use DataSift\Stone\ExceptionsLib\E5xx_NotImplemented;
use DataSift\Stone\LogLib\Log;

class FormDetermine extends BrowserDetermine
{
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
}