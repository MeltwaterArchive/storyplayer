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
 * @package   Stone/ProcessLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */

namespace DataSift\Stone\ProcessLib;

use DataSift\Stone\LogLib\Log;

/**
 * represents a single sub-process that we have started
 *
 * @category  Libraries
 * @package   Stone/ProcessLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class SubProcess
{
	/**
	 * the process ID of our subprocess
	 * @var int|null
	 */
	protected $pid = null;

	/**
	 * constructor
	 *
	 * registers a shutdown handler to make sure we always clean up
	 * after ourselves
	 */
	public function __construct()
	{
		// make sure we stop our subprocess
		register_shutdown_function(array($this, "stopChildProcess"));
	}

	/**
	 * get the process ID of the child process
	 *
	 * @return int|null
	 */
	public function getPid()
	{
	    return $this->pid;
	}

	/**
	 * set the process ID of the child process
	 *
	 * @param int $pid
	 *        the process ID to remember
	 * @return SubProcess
	 *         $this for fluent interface support
	 */
	public function setPid($pid)
	{
	    $this->pid = $pid;

	    return $this;
	}

	/**
	 * reset the process ID
	 *
	 * @return void
	 */
	public function resetPid()
	{
		$this->pid = null;
	}

	/**
	 * is the child process that we (may have) started currently running?
	 *
	 * @return boolean
	 */
	public function isChildProcessRunning()
	{
		// do we *have* a process ID?
		if ($this->pid === null) {
			return false;
		}

		// is the process currently running?
		if (!posix_kill($this->pid, 0)) {
			// no - so forget its process ID
			$this->pid = null;

			// all done
			return false;
		}

		// if we get here, then the process is running
		return true;
	}

	/**
	 * create the sub-process
	 *
	 * @param  string $command
	 *         the sub-process to create
	 * @param  array $params
	 *         a list of the params to pass to $command
	 * @return void
	 */
	public function startChildProcess($command, $params)
	{
		// fork
		$pid = pcntl_fork();

		// did it work?
		if ($pid == -1) {
			// it really didn't work
			//
			// we don't know why
			throw new E5xx_ForkFailed();
		}

		// if we get here, then we have a parent process (the original
		// process) and a child process ($command)

		// are we the parent process?
		if ($pid) {
			// yes, we are
			$this->setPid($pid);
			return;
		}

		// if we get here, we are the child process
		pcntl_exec($command, $params, $_ENV);

		// this line is ONLY reached if the pcntl_exec() failed
		throw new E5xx_DidNotStartChildProcess($command);
	}

	/**
	 * stop the child process, if it is running
	 *
	 * @return void
	 */
	public function stopChildProcess()
	{
		if (!$this->isChildProcessRunning())
		{
			// nothing to do
			return;
		}

		// remember the process ID so that we can kill it
		$pid = $this->pid;

		// we don't want to remember the process ID once it has been
		// stopped
		$this->resetPid();

		// find a list of all the child processes
		$output = '';
		exec("ps -ef | awk '\$3 == '{$pid}' { print \$2 }'", $output, $returnCode);
		if ($returnCode) {
			// no awk available?
		}

		foreach ($output as $childProcessId) {
			$this->killProcess($childProcessId);
		}

		// now, kill off the process we started
		$this->killProcess($pid);
	}

	protected function killProcess($pid)
	{
		// tell the process to terminate
		Log::write(Log::LOG_DEBUG, "sending SIGTERM to pid '{$pid}'");
		posix_kill($pid, SIGTERM);

		// is it still running?
		if (!posix_kill($pid, 0)) {
			// no
			return;
		}

		// yes - give it up to 2 secs to tidy up
		for ($i = 0; $i < 40; $i++) {
			// sleep for a little bit to avoid burning CPU
			usleep(50000);

			// is it still running?
			if (!posix_kill($pid, 0)) {
				// no
				return;
			}
		}

		// if we get here, we're done waiting
		Log::write(Log::LOG_DEBUG, "sending SIGKILL to pid '{$pid}'");
		posix_kill($pid, SIGKILL);
	}
}