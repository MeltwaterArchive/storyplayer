<?php

namespace DataSift\Storyplayer\PlayerLib;

use DataSift\Stone\ObjectLib\BaseObject;

class StoryContext extends BaseObject
{
	public $story;

	/**
	 * the details about the user that has been chosen
	 *
	 * @var DataSift\Stone\ObjectLib\BaseObject
	 */
	public $user;

	/**
	 * the details of the environment, taken directly from the app's JSON
	 * config file
	 *
	 * @var DataSift\Stone\ObjectLib\BaseObject
	 */
	public $env;

	/**
	 * persistent config (users, vms, etc) that gets cached to disk
	 * between runs
	 *
	 * @var DataSift\Stone\ObjectLib\BaseObject
	 */
	public $runtime;

	public function __construct()
	{
		$this->user    = new BaseObject;
		$this->env     = new BaseObject;
		$this->runtime = new BaseObject;

		// we need to provide information about the machine that we
		// are running on
		$this->env->host = new BaseObject;
		list($this->env->host->networkInterface, $this->env->host->ipAddress) = $this->getHostIpAddress();
		$this->env->host->name = $this->getHostName();
	}

	protected function getHostIpAddress()
	{
		// step 1 - how many adapters do we have on this box?
		$BIN_DIR = BIN_DIR;
		$adapters = trim(`{$BIN_DIR}/get-ip -l | tr '\n' ' '`);
		if (empty($adapters)) {
			throw new Exception("unable to parse host machine network adapters list");
		}
		$adapters = explode(' ', $adapters);

		// step 2 - find an adapter that is most likely to have the IP address
		// that we want
		//
		// note: am not sure that the search list for OSX interfaces is
		// reliable :(
		$searchList = array("br0", "p20p1", "eth0", "en2", "en0", "en1");
		foreach ($searchList as $adapterToTest) {
			// skip over any adapters that don't exist on this machine
			if (!in_array($adapterToTest, $adapters)) {
				continue;
			}

			// we think the adapter is present
			//
			// does it have an IP address?
			$ipAddress = trim(`{$BIN_DIR}/get-ip -i {$adapterToTest}`);

			// did we get back a valid IP address?
			$parts = explode('.', $ipAddress);
			if (count($parts) == 4) {
				// success!
				return array($adapterToTest, $ipAddress);
			}
		}

		// if we get here, we could not determine the IP address of our
		// host :(
		//
		// this sucks
		throw new Exception("could not determine IP address of host machine");
	}

	protected function getHostName()
	{
		return `hostname`;
	}
}
