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
 * @category    Libraries
 * @package     Storyplayer/CommandLib
 * @author      Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright   2011-present Mediasift Ltd www.datasift.com
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\CommandLib;

use Mockery;
use PHPUnit_Framework_TestCase;
use stdClass;
use DataSift\Storyplayer\Injectables;
use DataSift\Storyplayer\PlayerLib\Action_LogItem;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

class SshClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers DataSift\Storyplayer\CommandLib\SshClient::__construct
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // setup your test

        $i  = new Injectables;
        $i->initOutputSupport();
        $i->initRuntimeConfigSupport($i);
        $st = new StoryTeller($i);

        // ----------------------------------------------------------------
        // perform the change

        $obj = new SshClient($st);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof SshClient);
    }

    /**
     * @covers DataSift\Storyplayer\CommandLib\SshClient::getIpAddress
     * @covers DataSift\Storyplayer\CommandLib\SshClient::hasIpAddress
     * @covers DataSift\Storyplayer\CommandLib\SshClient::setIpAddress
     */
    public function testCanGetAndSetTargetIpAddress()
    {
        // ----------------------------------------------------------------
        // setup your test

        // our $st object
        $i  = new Injectables;
        $i->initOutputSupport();
        $i->initRuntimeConfigSupport($i);
        $st = new StoryTeller($i);

        // our test subject
        $obj = new SshClient($st);

        // the IP address to use
        $expectedIpAddress = '192.168.1.5';

        // make sure we're starting with no IP address
        $this->assertFalse($obj->hasIpAddress());

        // ----------------------------------------------------------------
        // perform the change

        $obj->setIpAddress($expectedIpAddress);
        $actualIpAddress = $obj->getIpAddress();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedIpAddress, $actualIpAddress);
        $this->assertTrue($obj->hasIpAddress());
    }

    /**
     * @covers DataSift\Storyplayer\CommandLib\SshClient::__construct
     * @covers DataSift\Storyplayer\CommandLib\SshClient::getDefaultSshOptions
     * @covers DataSift\Storyplayer\CommandLib\SshClient::getSshOptions
     */
    public function testStartsWithStdinAttachedToDevNull()
    {
        // ----------------------------------------------------------------
        // setup your test

        // our $st object
        $i  = new Injectables;
        $i->initOutputSupport();
        $i->initRuntimeConfigSupport($i);
        $st = new StoryTeller($i);

        // our test subject
        $obj = new SshClient($st);

        // ----------------------------------------------------------------
        // perform the change

        $actualOptions = $obj->getSshOptions();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals([ '-n'], $actualOptions);
    }

    /**
     * @covers DataSift\Storyplayer\CommandLib\SshClient::__construct
     * @covers DataSift\Storyplayer\CommandLib\SshClient::getSshOptions
     * @covers DataSift\Storyplayer\CommandLib\SshClient::addSshOption
     */
    public function testCanAddSshOptionsWhenInstantiating()
    {
        // ----------------------------------------------------------------
        // setup your test

        // our $st object
        $i  = new Injectables;
        $i->initOutputSupport();
        $i->initRuntimeConfigSupport($i);
        $st = new StoryTeller($i);

        // the options to set
        // it doesn't matter if they are valid - we simply expect them
        // to be parrotted back to us later on
        // what are the default options?
        $inputOptions = [
            "-O",
            "--fake-option"
        ];

        // ----------------------------------------------------------------
        // perform the change

        $obj = new SshClient($st, $inputOptions);
        $expectedOptions = array_merge($obj->getDefaultSshOptions(), $inputOptions);
        $actualOptions = $obj->getSshOptions();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedOptions, $actualOptions);
    }

    /**
     * @covers DataSift\Storyplayer\CommandLib\SshClient::__construct
     * @covers DataSift\Storyplayer\CommandLib\SshClient::getSshOptions
     * @covers DataSift\Storyplayer\CommandLib\SshClient::addSshOption
     */
    public function testCanAddSshOptions()
    {
        // ----------------------------------------------------------------
        // setup your test

        // our $st object
        $i  = new Injectables;
        $i->initOutputSupport();
        $i->initRuntimeConfigSupport($i);
        $st = new StoryTeller($i);

        // our test subject
        $obj = new SshClient($st);

        // the options to set
        // it doesn't matter if they are valid - we simply expect them
        // to be parrotted back to us later on
        $inputOptions = [
            "-O",
            "--fake-option"
        ];

        // what are the default options?
        $expectedOptions = $obj->getDefaultSshOptions();

        // ----------------------------------------------------------------
        // perform the change

        foreach ($inputOptions as $option) {
            $obj->addSshOption($option);
            $expectedOptions[] = $option;
        }
        $actualOptions = $obj->getSshOptions();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedOptions, $actualOptions);
    }
    /**
     * @covers DataSift\Storyplayer\CommandLib\SshClient::getSshOptions
     */
    public function testCanGetSshOptionsAsArray()
    {
        // ----------------------------------------------------------------
        // setup your test

        // our $st object
        $i  = new Injectables;
        $i->initOutputSupport();
        $i->initRuntimeConfigSupport($i);
        $st = new StoryTeller($i);

        // our test subject
        $obj = new SshClient($st);

        // what are the default options?
        $expectedOptions = $obj->getDefaultSshOptions();

        // ----------------------------------------------------------------
        // perform the change

        $actualOptions = $obj->getSshOptions();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue(is_array($actualOptions));
        $this->assertEquals($expectedOptions, $actualOptions);
    }

    /**
     * @covers DataSift\Storyplayer\CommandLib\SshClient::getSshOptionsForUse
     */
    public function testCanGetSshOptionsAsString()
    {
        // ----------------------------------------------------------------
        // setup your test

        // our $st object
        $i  = new Injectables;
        $i->initOutputSupport();
        $i->initRuntimeConfigSupport($i);
        $st = new StoryTeller($i);

        // our test subject
        $obj = new SshClient($st);

        // what are the default options?
        $expectedOptions = implode(' ', $obj->getDefaultSshOptions());

        // the options to set
        // it doesn't matter if they are valid - we simply expect them
        // to be parrotted back to us later on
        $inputOptions = [
            "-O",
            "--fake-option"
        ];

        // ----------------------------------------------------------------
        // perform the change

        foreach ($inputOptions as $option) {
            $obj->addSshOption($option);
            $expectedOptions .= ' ' . $option;
        }
        $actualOptions = $obj->getSshOptionsForUse();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue(is_string($actualOptions));
        $this->assertEquals($expectedOptions, $actualOptions);
    }

    /**
     * @covers DataSift\Storyplayer\CommandLib\SshClient::getSshUsername
     * @covers DataSift\Storyplayer\CommandLib\SshClient::hasSshUsername
     * @covers DataSift\Storyplayer\CommandLib\SshClient::setSshUsername
     */
    public function testCanGetAndSetSshUsername()
    {
        // ----------------------------------------------------------------
        // setup your test

        // our $st object
        $i  = new Injectables;
        $i->initOutputSupport();
        $i->initRuntimeConfigSupport($i);
        $st = new StoryTeller($i);

        // our test subject
        $obj = new SshClient($st);

        // what username will we use?
        $expectedResult = 'freddybloggs';

        // make sure that (if there is ever a default username), it isn't
        // the string we're testing with
        $this->assertNotEquals($expectedResult, $obj->getSshUsername());

        // and, make sure we start with no username
        $this->assertFalse($obj->hasSshUsername());

        // ----------------------------------------------------------------
        // perform the change

        $obj->setSshUsername($expectedResult);
        $actualResult = $obj->getSshUsername();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue(is_string($actualResult));
        $this->assertEquals($expectedResult, $actualResult);
        $this->assertTrue($obj->hasSshUsername());
    }

    /**
     * @covers DataSift\Storyplayer\CommandLib\SshClient::getSshKey
     * @covers DataSift\Storyplayer\CommandLib\SshClient::setSshKey
     */
    public function testCanGetAndSetSshKey()
    {
        // ----------------------------------------------------------------
        // setup your test

        // our $st object
        $i  = new Injectables;
        $i->initOutputSupport();
        $i->initRuntimeConfigSupport($i);
        $st = new StoryTeller($i);

        // our test subject
        $obj = new SshClient($st);

        // what key will we use?
        $expectedResult = 'id_rsa';

        // make sure that (if there is ever a default ssh key), it isn't
        // the string we're testing with
        $this->assertNotEquals($expectedResult, $obj->getSshKey());

        // ----------------------------------------------------------------
        // perform the change

        $obj->setSshKey($expectedResult);
        $actualResult = $obj->getSshKey();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue(is_string($actualResult));
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers DataSift\Storyplayer\CommandLib\SshClient::getSshKeyForUse
     */
    public function testCanGetSshKeyForUse()
    {
        // ----------------------------------------------------------------
        // setup your test

        // our $st object
        $i  = new Injectables;
        $i->initOutputSupport();
        $i->initRuntimeConfigSupport($i);
        $st = new StoryTeller($i);

        // our test subject
        $obj = new SshClient($st);

        // what key will we use?
        $expectedKey = 'id_rsa';
        $expectedResult = "-i '" . $expectedKey . "'";

        // make sure that (if there is ever a default ssh key), it isn't
        // the string we're testing with
        $this->assertNotEquals($expectedResult, $obj->getSshKey());

        // ----------------------------------------------------------------
        // perform the change

        $obj->setSshKey($expectedKey);
        $actualResult = $obj->getSshKeyForUse();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue(is_string($actualResult));
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers DataSift\Storyplayer\CommandLib\SshClient::getSshKeyForUse
     */
    public function testReturnsEmptyStringWhenNoSshKeySet()
    {
        // ----------------------------------------------------------------
        // setup your test

        // our $st object
        $i  = new Injectables;
        $i->initOutputSupport();
        $i->initRuntimeConfigSupport($i);
        $st = new StoryTeller($i);

        // our test subject
        $obj = new SshClient($st);

        // what result do we expect?
        $expectedResult = "";

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj->getSshKeyForUse();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue(is_string($actualResult));
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers DataSift\Storyplayer\CommandLib\SshClient::convertParamsForUse
     */
    public function testCanEscapeParamsForRemoteGlobbing()
    {
        // ----------------------------------------------------------------
        // setup your test

        $i  = new Injectables;
        $i->initOutputSupport();
        $i->initRuntimeConfigSupport($i);
        $st = new StoryTeller($i);

        $obj   = new SshClient($st);

        $inputParams    = "ls *";
        $expectedParams = "ls '*'";

        // ----------------------------------------------------------------
        // perform the change

        $actualParams = $obj->convertParamsForUse($inputParams);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedParams, $actualParams);
    }

    /**
     * @covers DataSift\Storyplayer\CommandLib\SshClient::runCommand
     */
    public function testCanRunCommands()
    {
        // ----------------------------------------------------------------
        // setup your test

        // the SSH username we are going to use
        $username = 'fred';

        // the ipAddress we are going to use
        $ipAddress = '10.0.45.254';

        // the command we are going to run
        $command = "ls /tmp";

        // the full SSH command should be ...
        $fullCommand = 'ssh -o StrictHostKeyChecking=no '
            . ' -n '
            . $username . '@' . $ipAddress
            . ' "' . $command . '"';

        // the CommandResult we want for this test
        $expectedReturnCode = 0;
        $expectedOutput = 'success!';
        $cmdResult = new CommandResult($expectedReturnCode, $expectedOutput);

        // our mocked $st
        $st = Mockery::mock("DataSift\Storyplayer\PlayerLib\StoryTeller");

        // our fake CommandRunner
        $cmdRunner = Mockery::mock("DataSift\Storyplayer\CommandLib\CommandRunner");
        $cmdRunner->shouldReceive('runSilently')->once()->with($st, $fullCommand)->andReturn($cmdResult);

        // our fake Output for logging purposes
        $output = Mockery::mock("DataSift\Storyplayer\Output");
        $output->shouldReceive('logPhaseActivity');

        // our fake injectables container
        $injectables = new stdClass;
        $injectables->output = $output;

        // our mocked $st needs to do things too
        //
        // we have to put this down here, because we need to pass $st into
        // our mocked CommandRunner
        $st->shouldReceive('startAction')->once()->andReturn(new Action_LogItem($injectables, 1));
        $st->shouldReceive('getNewCommandRunner')->andReturn($cmdRunner);

        // our test subject
        $obj = new SshClient($st);

        // the things we need to set for the command to be attempted
        $obj->setSshUsername($username);
        $obj->setIpAddress($ipAddress);

        // ----------------------------------------------------------------
        // perform the change

        $result = $obj->runCommand($command);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result instanceof CommandResult);
        $this->assertEquals($expectedReturnCode, $result->returnCode);
        $this->assertEquals($expectedOutput, $result->output);

        // all done
        Mockery::close();
    }

    /**
     * @covers DataSift\Storyplayer\CommandLib\SshClient::runCommand
     * @covers DataSift\Storyplayer\CommandLib\E4xx_NeedSshUsername::__construct
     */
    public function testMustProvideUsernameToRunCommands()
    {
        // ----------------------------------------------------------------
        // setup your test

        // our mocked $st
        $st = Mockery::mock("DataSift\Storyplayer\PlayerLib\StoryTeller");

        // our fake Output for logging purposes
        $output = Mockery::mock("DataSift\Storyplayer\Output");
        $output->shouldReceive('logPhaseActivity');

        // our fake injectables container
        $injectables = new stdClass;
        $injectables->output = $output;

        // our mocked $st needs to do things too
        //
        // we have to put this down here, because we need to pass $st into
        // our mocked CommandRunner
        $st->shouldReceive('startAction')->once()->andReturn(new Action_LogItem($injectables, 1));

        // our test subject
        $obj = new SshClient($st);

        // ----------------------------------------------------------------
        // perform the change

        $caughtException = false;
        try {
            $result = $obj->runCommand('ls /tmp');
        }
        catch (E4xx_NeedSshUsername $e) {
            $caughtException = true;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($caughtException);

        // all done
        Mockery::close();
    }

    /**
     * @covers DataSift\Storyplayer\CommandLib\SshClient::runCommand
     * @covers DataSift\Storyplayer\CommandLib\E4xx_NeedIpAddress::__construct
     */
    public function testMustProvideIpAddressToRunCommands()
    {
        // ----------------------------------------------------------------
        // setup your test

        // our mocked $st
        $st = Mockery::mock("DataSift\Storyplayer\PlayerLib\StoryTeller");

        // our fake Output for logging purposes
        $output = Mockery::mock("DataSift\Storyplayer\Output");
        $output->shouldReceive('logPhaseActivity');

        // our fake injectables container
        $injectables = new stdClass;
        $injectables->output = $output;

        // our mocked $st needs to do things too
        //
        // we have to put this down here, because we need to pass $st into
        // our mocked CommandRunner
        $st->shouldReceive('startAction')->once()->andReturn(new Action_LogItem($injectables, 1));

        // our test subject
        $obj = new SshClient($st);
        $obj->setSshUsername('fred');

        // ----------------------------------------------------------------
        // perform the change

        $caughtException = false;
        try {
            $result = $obj->runCommand('ls /tmp');
        }
        catch (E4xx_NeedIpAddress $e) {
            $caughtException = true;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($caughtException);

        // all done
        Mockery::close();
    }
}
