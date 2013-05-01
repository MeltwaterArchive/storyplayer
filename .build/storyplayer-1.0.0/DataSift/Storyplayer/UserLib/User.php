<?php

namespace DataSift\Storyplayer\UserLib;

use stdClass;
use DataSift\Stone\ObjectLib\BaseObject;

class User extends BaseObject
{
	public $roles;

	public function __construct()
	{
		$this->roles = new stdClass;
	}

	public function addRole($role)
	{
		$this->roles->$role = $role;
	}

	public function hasRole($role)
	{
		if (isset($this->roles->$role)) {
			return true;
		}

		return false;
	}

	public function removeRole($role)
	{
		if (isset($this->roles->$role)) {
			unset($this->roles->$role);
		}
	}

	public function removeAllRoles()
	{
		$this->roles = new stdClass;
	}
}