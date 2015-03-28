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

namespace Prose;

use Exception;
use PDO;
use PDOException;
use PDOStatement;

/**
 * work with the results of running a PDO query
 *
 * great for managing test data
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class FromPDOStatement extends Prose
{
	public function __construct($st, $args)
	{
		// call our parent first
		parent::__construct($st, $args);

		// make sure we have a PDO connection to use
		if (!isset($this->args[0])) {
			throw new E5xx_ActionFailed(__METHOD__, "param #1 must be a valid PDOStatement object");
		}
		if (!$this->args[0] instanceof PDOStatement) {
			throw new E5xx_ActionFailed(__METHOD__, "param #1 must be an instance of PDOStatement");
		}
	}

	public function fetchAll()
	{
		// what are we doing?
		$log = usingLog()->startAction("fetch all rows from the PDO query result");

		try
		{
			$rows = $this->args[0]->fetchAll();

			// all done
			$log->endAction($rows);

			return $rows;
		}
		catch (Exception $e)
		{
			throw new E5xx_ActionFailed(__METHOD__, $e->getMessage());
		}
	}

	public function fetchAssoc()
	{
		// what are we doing?
		$log = usingLog()->startAction("fetch 1 row from the PDO query result");

		try
		{
			$row = $this->args[0]->fetch(PDO::FETCH_ASSOC);

			// all done
			$log->endAction($row);

			return $row;
		}
		catch (Exception $e)
		{
			throw new E5xx_ActionFailed(__METHOD__, $e->getMessage());
		}
	}

	public function fetchNum()
	{
		// what are we doing?
		$log = usingLog()->startAction("fetch 1 row from the PDO query result");

		try
		{
			$row = $this->args[0]->fetch(PDO::FETCH_NUM);

			// all done
			$log->endAction($row);

			return $row;
		}
		catch (Exception $e)
		{
			throw new E5xx_ActionFailed(__METHOD__, $e->getMessage());
		}
	}

	public function fetchObj()
	{
		// what are we doing?
		$log = usingLog()->startAction("fetch 1 row from the PDO query result");

		try
		{
			$row = $this->args[0]->fetch(PDO::FETCH_OBJ);

			// all done
			$log->endAction($row);

			return $row;
		}
		catch (Exception $e)
		{
			throw new E5xx_ActionFailed(__METHOD__, $e->getMessage());
		}
	}

	public function getRowCount()
	{
		// what are we doing?
		$log = usingLog()->startAction("how many rows were affected by the last SQL query?");

		// get the answer
		$rowCount = $this->args[0]->rowCount();

		// all done
		$log->endAction($rowCount);
		return $rowCount;
	}
}