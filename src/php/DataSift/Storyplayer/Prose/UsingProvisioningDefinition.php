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

namespace DataSift\Storyplayer\Prose;

use DataSift\Storyplayer\ProvisioningLib\ProvisioningDefinition;
use DataSift\Storyplayer\ProvisioningLib\DelayedProvisioningDefinitionAction;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Stone\DataLib\DataPrinter;
use DataSift\Stone\ObjectLib\BaseObject;

/**
 * Support for populating a provisioning definition
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class UsingProvisioningDefinition extends Prose
{
	public function __construct(StoryTeller $st, $args)
	{
		// call our parent
		parent::__construct($st, $args);

		// $args[0] should be our provisioning block
		if (!isset($args[0]) || ! $args[0] instanceof ProvisioningDefinition) {
			throw new E5xx_ActionFailed(__METHOD__, "Param #0 must be a ProvisioningDefinition object");
		}
	}

	public function addHost($hostId)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("add host '{$hostId}' to provisioning definition");

		// create the host
		if (!isset($this->args[0]->$hostId)) {
			$this->args[0]->$hostId = new BaseObject();
		}

		// all done
		$log->endAction();
	}

	public function addRole($roleName)
	{
		// build our callable
		$action = function($st, $def, $hostId) use($roleName) {
			// what are we doing?
			$log = $st->startAction("add role '{$roleName}' to host '{$hostId}'");

			// make sure we have an entry for this host
			if (!isset($def->$hostId)) {
				$def->$hostId = new BaseObject();
			}

			// create our list of roles if we don't have one
			if (!isset($def->$hostId->roles)) {
				$def->$hostId->roles = array();
			}

			// add the role
			$def->$hostId->roles[] = $roleName;

			// all done
			$log->endAction();
		};

		// build our return object
		$return = new DelayedProvisioningDefinitionAction (
			$this->st,
			$this->args[0],
			$action
		);

		// all done
		return $return;
	}

	public function addParams($params)
	{
		// build our callable
		$action = function($st, $def, $hostId) use($params) {
			// convert the params into something we can log
			$printer = new DataPrinter();
			$logParams = $printer->convertToString($params);

			// what are we doing?
			$log = $st->startAction("add params '{$logParams}' to host '{$hostId}'");

			// make sure we have an entry for this host
			if (!isset($def->$hostId)) {
				$def->$hostId = new BaseObject();
			}

			// add our params
			$def->$hostId->params = $params;

			// all done
			$log->endAction();
		};

		// build our return object
		$return = new DelayedProvisioningDefinitionAction (
			$this->st,
			$this->args[0],
			$action
		);

		// all done
		return $return;
	}

	public function usePlaybook($playbookName)
	{
		// build our callable
		$action = function($st, $def, $hostId) use($playbookName) {
			// what are we doing?
			$log = $st->startAction("use top-level playbook '{$playbookName}' to host '{$hostId}'");

			// make sure we have an entry for this host
			if (!isset($def->$hostId)) {
				$def->$hostId = new BaseObject();
			}

			// add the playbook
			$def->$hostId->playbook = $playbookName;

			// all done
			$log->endAction();
		};

		// build our return object
		$return = new DelayedProvisioningDefinitionAction (
			$this->st,
			$this->args[0],
			$action
		);

		// all done
		return $return;
	}}