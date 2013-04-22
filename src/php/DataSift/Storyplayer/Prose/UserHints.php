<?php

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Stone\ExceptionsLib\E5xx_NotImplemented;

class UserHints extends ProseActions
{
	public function addsRole($role)
	{
		// shorthand
		$st = $this->st;
		$user = $st->getUser();

		$user->addRole($role);
	}

	public function creates($role)
	{
		// shorthand
		$st = $this->st;
		$user = $st->getUser();

		$user->removeAllRoles();
		$user->addRole($role);
	}

	public function removesRole($role)
	{
		// shorthand
		$st = $this->st;
		$user = $st->getUser();

		$user->removeRole($role);
	}
}