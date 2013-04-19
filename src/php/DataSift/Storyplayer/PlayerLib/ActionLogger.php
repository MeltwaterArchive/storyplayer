<?php

namespace DataSift\Storyplayer\PlayerLib;

class ActionLogger
{
	private $actions = array();

	public function startAction($user, $text)
	{
		// is this our first action?
		if (count($this->actions) == 0)
		{
			$openItem = new ActionLogItem(1);
			$this->actions[] = $openItem;
		}
		else {
			// do we have any open actions to nest inside?
			$endItem  = end($this->actions);
			if ($endItem->isOpen()) {
				// this is a new nested item
				$openItem = $endItem->newNestedAction();
			}
			else {
				$openItem = new ActionLogItem(1);
				$this->actions[] = $openItem;
			}
		}

		return $openItem->startAction($user, $text);
	}

	public function closeAllOpenActions()
	{
		// do we have any empty log items?
		if (count($this->actions) == 0)
		{
			return;
		}

		$endItem = end($this->actions);
		if ($endItem->isOpen()) {
			$endItem->closeAllOpenActions();
		}

		$endItem->endAction();
	}
}