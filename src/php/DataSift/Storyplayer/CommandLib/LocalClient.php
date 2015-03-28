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
 * @package   Storyplayer/CommandLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\CommandLib;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use Phix_Project\ContractLib2\Contract;

/**
 * helpers for interacting with the local operating system
 *
 * @category  Libraries
 * @package   Storyplayer/CommandLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

class LocalClient implements CommandClient
{
	/**
	 *
	 * @var StoryTeller
	 */
	protected $st;

	public function __construct(StoryTeller $st)
	{
		// remember for future use
		$this->st = $st;
	}

	/**
	 *
	 * @param  string $params
	 * @return string
	 * @deprecated
	 */
	public function convertParamsForUse($params)
	{
		// vet our input
		Contract::RequiresValue($params, is_string($params));
		// we don't mind if the params are empty

		// our list of what to convert from
		$convertFrom = [
			'\'',
			'*'
		];

		// our list of what to convert to
		$convertTo = [
			'\\\'',
			'\'*\''
		];

		// our return value
		$result = str_replace($convertFrom, $convertTo, $params);

		// all done
		return rtrim($result);
	}

	/**
	 *
	 * @param  string $command
	 * @return CommandResult
	 */
	public function runCommand($command)
	{
		// vet our input
		Contract::RequiresValue($command, is_string($command));
		Contract::RequiresValue($command, !empty($command));

		// make the params printable / executable
		// $printableParams = $this->convertParamsForUse($params);

		// what are we doing?
		$log = usingLog()->startAction("run command '{$command}' against localhost ");

		// build the full command
		// <command> <command-args>
		//    the command to run on the local OS
		//    (assumes the command will be globbed by the local shell)
		//$fullCommand = str_replace('"', '\"', $command);
		$fullCommand = $command;

		// run the command
		$commandRunner = $this->st->getNewCommandRunner();
		$result = $commandRunner->runSilently($fullCommand);

		// all done
		$log->endAction("return code was '{$result->returnCode}'");
		return $result;
	}

    public function downloadFile($sourceFilename, $destFilename)
    {
        // vet our input
        Contract::RequiresValue($sourceFilename, is_string($sourceFilename));
        Contract::RequiresValue($sourceFilename, !empty($sourceFilename));
        Contract::RequiresValue($destFilename, is_string($destFilename));
        Contract::RequiresValue($destFilename, !empty($destFilename));

        // make the params printable / executable
        // $printableParams = $this->convertParamsForUse($params);

        // what are we doing?
        $log = usingLog()->startAction("copy file '{$sourceFilename}' to localhost as '{$destFilename}'");

        // build the full command
        //
        $fullCommand = 'cp '
                     . "'" . $sourceFilename . "' "
                     . "'" . $destFilename . "'";

        // run the command
        $commandRunner = $this->st->getNewCommandRunner();
        $result = $commandRunner->runSilently($fullCommand);

        // all done
        $log->endAction("return code was '{$result->returnCode}'");
        return $result;
    }

    public function uploadFile($sourceFilename, $destFilename)
    {
        // vet our input
        Contract::RequiresValue($sourceFilename, is_string($sourceFilename));
        Contract::RequiresValue($sourceFilename, !empty($sourceFilename));
        Contract::RequiresValue($sourceFilename, is_file($sourceFilename));
        Contract::RequiresValue($destFilename, is_string($destFilename));
        Contract::RequiresValue($destFilename, !empty($destFilename));

        // make the params printable / executable
        // $printableParams = $this->convertParamsForUse($params);

        // what are we doing?
        $log = usingLog()->startAction("copy file '{$sourceFilename}' to localhost as '{$destFilename}'");

        // build the full command
        //
        $fullCommand = 'cp '
                     . "'" . $sourceFilename . "' "
                     . "'" . $destFilename . "'";

        // run the command
        $commandRunner = $this->st->getNewCommandRunner();
        $result = $commandRunner->runSilently($fullCommand);

        // all done
        $log->endAction("return code was '{$result->returnCode}'");
        return $result;
    }
}