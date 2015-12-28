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
 * @package   Storyplayer
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\ProvisioningLib\ProvisioningDefinition;
use DataSift\Stone\HttpLib\HttpClientResponse;
use Predis\Client as PredisClient;

// ==================================================================
//
// a list of all the Prose modules that we are exposing
//
// this file registers global functions for each of our Prose modules
// it is a great help for everyone who uses autocompletion in their
// editor / IDE of choice
//
// keep this list in alphabetical order, please!
//
// ------------------------------------------------------------------

use Storyplayer\SPv2\Modules\Asserts;
use Storyplayer\SPv2\Modules\Browser;
use Storyplayer\SPv2\Modules\Device;
use Storyplayer\SPv2\Modules\Exceptions;
use Storyplayer\SPv2\Modules\Host;
use Storyplayer\SPv2\Modules\HTTP;
use Storyplayer\SPv2\Modules\Log;
use Storyplayer\SPv2\Modules\Supervisor;
use Storyplayer\SPv2\Modules\Users;
use Storyplayer\SPv2\Modules\ZeroMQ;

use Prose\CleanupProcesses;
use Prose\CleanupRoles;
use Prose\CleanupTargets;
use Prose\ExpectsEc2Image;
use Prose\ExpectsFailure;
use Prose\ExpectsGraphite;
use Prose\ExpectsProcessesTable;
use Prose\ExpectsRolesTable;
use Prose\ExpectsRuntimeTable;
use Prose\ExpectsShell;
use Prose\ExpectsUuid;
use Prose\FromArray;
use Prose\FromAws;
use Prose\FromCheckpoint;
use Prose\FromConfig;
use Prose\FromCurl;
use Prose\FromEc2;
use Prose\FromEc2Instance;
use Prose\FromEnvironment;
use Prose\FromFacebook;
use Prose\FromFile;
use Prose\FromGraphite;
use Prose\FromIframe;
use Prose\FromPDOStatement;
use Prose\FromProcessesTable;
use Prose\FromRedisConn;
use Prose\FromRolesTable;
use Prose\FromRuntimeTable;
use Prose\FromSauceLabs;
use Prose\FromShell;
use Prose\FromStoryplayer;
use Prose\FromString;
use Prose\FromSystemUnderTest;
use Prose\FromTargetsTable;
use Prose\FromTestEnvironment;
use Prose\FromUuid;
use Prose\UsingCheckpoint;
use Prose\UsingEc2;
use Prose\UsingEc2Instance;
use Prose\UsingFacebookGraphApi;
use Prose\UsingFile;
use Prose\UsingFirstHostWithRole;
use Prose\UsingHornet;
use Prose\UsingIframe;
use Prose\UsingMysql;
use Prose\UsingPDO;
use Prose\UsingPDODB;
use Prose\UsingProcessesTable;
use Prose\UsingProvisioning;
use Prose\UsingProvisioningDefinition;
use Prose\UsingProvisioningEngine;
use Prose\UsingRedis;
use Prose\UsingRedisConn;
use Prose\UsingReporting;
use Prose\UsingRolesTable;
use Prose\UsingRuntimeTable;
use Prose\UsingSauceLabs;
use Prose\UsingSavageD;
use Prose\UsingShell;
use Prose\UsingTargetsTable;
use Prose\UsingTimer;
use Prose\UsingVagrant;
use Prose\UsingYamlFile;
use Prose\UsingZookeeper;

use Prose\E5xx_ActionFailed;

/**
 * returns the AssertsArray module
 *
 * @param  array $actual
 *         the array to be tested
 * @return Storyplayer\SPv2\Modules\Asserts\AssertsArray
 */
function assertsArray($actual)
{
    return Asserts::assertsArray($actual);
}

/**
 * returns the AssertsBoolean module
 *
 * @param  boolean $actual
 *         the data to be tested
 * @return Storyplayer\SPv2\Modules\Asserts\AssertsBoolean
 */
function assertsBoolean($actual)
{
    return Asserts::assertsBoolean($actual);
}

/**
 * returns the AssertsDouble module
 *
 * @param  double $actual
 *         the data to be tested
 * @return Storyplayer\SPv2\Modules\Asserts\AssertsDouble
 */
function assertsDouble($actual)
{
    return Asserts::assertsDouble($actual);
}

/**
 * returns the AssertsInteger module
 *
 * @param  int $actual
 *         the data to be tested
 * @return Storyplayer\SPv2\Modules\Asserts\AssertsInteger
 */
function assertsInteger($actual)
{
    return Asserts::assertsInteger($actual);
}

/**
 * returns the AssertsObject module
 *
 * @param  object $actual
 *         the data to be tested
 * @return Storyplayer\SPv2\Modules\Asserts\AssertsObject
 */
function assertsObject($actual)
{
    return Asserts::assertsObject($actual);
}

/**
 * returns the AssertsString module
 *
 * @param  string $actual
 *         the data to be tested
 * @return Storyplayer\SPv2\Modules\Asserts\AssertsString
 */
function assertsString($actual)
{
    return Asserts::assertsString($actual);
}

/**
 * returns the CleanupHosts module
 *
 * This module is used internally when Storyplayer shuts down to cleanup any
 * remaining entries in the internal 'hosts' table.
 *
 * You will never need to call this module from your stories.
 *
 * @param  string $key
 *         the name of the hosts table in the runtime table
 * @return \Storyplayer\SPv2\Modules\Host\CleanupHosts
 */
function cleanupHosts($key)
{
    return Host::cleanupHosts($key);
}

/**
 * returns the CleanupProcesses module
 *
 * This module is used internally when Storyplayer shuts down to cleanup any
 * remaining entries in the internal 'processes' table.
 *
 * You will never need to call this module from your stories.
 *
 * @param  string $key
 *         the name of the processes table in the runtime table
 * @return \Prose\CleanupProcesses
 */
function cleanupProcesses($key)
{
    return new CleanupProcesses(StoryTeller::instance(), [$key]);
}

/**
 * returns the CleanupRoles module
 *
 * This module is used internally when Storyplayer shuts down to cleanup any
 * remaining entries in the internal 'roles' table.
 *
 * You will never need to call this module from your stories.
 *
 * @param  string $key
 *         the name of the roles table in the runtime table
 * @return \Prose\CleanupRoles
 */
function cleanupRoles($key)
{
    return new CleanupRoles(StoryTeller::instance(), [$key]);
}

/**
 * returns the CleanupTargets module
 *
 * This module is used internally when Storyplayer shuts down to cleanup any
 * remaining entries in the internal 'targets' table.
 *
 * You will never need to call this module from your stories.
 *
 * @param  string $key
 *         the name of the targets table in the runtime table
 * @return \Prose\CleanupTargets
 */
function cleanupTargets($key)
{
    return new CleanupTargets(StoryTeller::instance(), [$key]);
}

/**
 * returns the ExpectsBrowser module
 *
 * This module provides support for checking the content of your web browser.
 *
 * Internally, this module interacts with your chosen browser (you chose by
 * using the --device command-line switch) using the Selenium WebDriver API.
 *
 * We've added a lot of helpful methods you can use to make it both quick
 * and natural to check the contents of the HTML page that's currently
 * open in the web browser.
 *
 * If the check fails, this module throws an exception.
 *
 * @return \Storyplayer\SPv2\Modules\Browser\ExpectsBrowser
 * @throws \Prose\E5xx_ExpectFailed
 */
function expectsBrowser()
{
    return Browser::expectsBrowser();
}

/**
 * returns the ExpectsEc2Image module
 *
 * This module provides support for testing the current state / condition
 * of an Amazon EC2 AMI. If the check fails, then this module will throw
 * an exception.
 *
 * @param  string $amiId
 *         the AMI ID that you want to check on
 * @return \Prose\ExpectsEc2Image
 * @throws \Prose\E5xx_ExpectFailed
 */
function expectsEc2Image($amiId)
{
    return new ExpectsEc2Image(StoryTeller::instance(), [$amiId]);
}

/**
 * returns the ExpectsFailure module
 *
 * Use this module to wrap operations that should not succeed, so that when
 * they do fail, they do not cause your story to fail. If the operations
 * actually end up succeeding, then this module will throw an exception.
 *
 * @return \Prose\ExpectsFailure
 * @throws \Prose\E5xx_ExpectFailed
 */
function expectsFailure()
{
    return new ExpectsFailure(StoryTeller::instance());
}

/**
 * returns the ExpectsHost module, with its hostId already set to the first
 * host in your test environment config file that has the given role.
 *
 * This module provides support for making your tests work on multiple
 * test environments. Instead of hard-coding hostIds into your stories,
 * use this module to find a host by its assigned role. That way, it doesn't
 * matter how many hosts are in different environments, or if their hostIds
 * are different.
 *
 * This module is normally used for testing the status of a host, such as if
 * a given process is currently running. If the check fails, this module
 * will throw an exception.
 *
 * @param  string $roleName
 *         the assigned role you're looking for
 * @return \Storyplayer\SPv2\Modules\Host\ExpectsFirstHostWithRole
 */
function expectsFirstHostWithRole($roleName)
{
    return Host::expectsFirstHostWithRole($roleName);
}

/**
 * returns the ExpectsForm module
 *
 * This module provides support for working with a given form on a HTML page
 * that's open in your browser. To use it, the form must have an 'id'
 * attribute set. Targetting a form with an 'id' helps make your test more
 * robust, especially if there are multiple forms on the same HTML page.
 *
 * Use the UsingBrowser module first to open the HTML page where the form is.
 *
 * @param  string $formId
 *         the 'id' attribute of the HTML form to use
 * @return \Storyplayer\SPv2\Modules\Browser\ExpectsForm
 * @throws \Prose\E5xx_ExpectFailed
 */
function expectsForm($formId)
{
    return Browser::expectsForm($formId);
}

/**
 * returns the ExpectsGraphite module
 *
 * This module provides support for retrieving data from Graphite. Graphite
 * is a round-robin database for capturing time-based series of data to be
 * displayed in graphs.
 *
 * You can use SavageD in your test environment to monitor the CPU and
 * memory usage of your system under test, logging the data to Graphite.
 * You can then use this module to make sure that your system under test
 * hasn't used too much CPU or RAM.
 *
 * @return \Prose\ExpectsGraphite
 * @throws \Prose\E5xx_ExpectFailed
 */
function expectsGraphite()
{
    return new ExpectsGraphite(StoryTeller::instance());
}

/**
 * returns the ExpectsHost module
 *
 * This module provides support for checking on something on a computer in
 * your test environment. If the check fails, an exception is thrown for you.
 *
 * In SPv1, it was common to call this module directly from your own stories.
 * In SPv2, you're much more likely to use one of our multi-host modules or
 * helpers (such as usingFirstHostWithRole) so that your stories are as
 * test-environment-independent as possible.
 *
 * @param  string $hostId
 *         the ID of the host to use
 * @return \Storyplayer\SPv2\Modules\Host\ExpectsHost
 */
function expectsHost($hostId)
{
    return Host::expectsHost($hostId);
}

/**
 * returns the ExpectsHostsTable module
 *
 * This module provides access to Storyplayer's internal list of computers
 * that are running in your test environment.
 *
 * This module is intended for internal use by Storyplayer. You should not
 * need to call this module from your own stories.
 *
 * @return \Storyplayer\SPv2\Modules\Host\ExpectsHostsTable
 */
function expectsHostsTable()
{
    return Host::expectsHostsTable();
}

/**
 * returns the ExpectsHttpResponse module
 *
 * This module provides a great way to test that you got the response that
 * you expected from your usingHttp() / fromHttp() call.
 *
 * If the response doesn't meet your expectations, an exception will be
 * thrown.
 *
 * @param  HttpClientResponse $httpResponse
 *         the return value from usingHttp()->post() et al
 * @return \Storyplayer\SPv2\Modules\HTTP\ExpectsHttpResponse
 * @throws \Prose\E5xx_ExpectFailed
 */
function expectsHttpResponse(HttpClientResponse $httpResponse)
{
    return HTTP::expectsHttpResponse($httpResponse);
}

/**
 * returns the ExpectsProcessesTable module
 *
 * This module provides access to Storyplayer's internal table of which
 * child processes are currently running.  Storyplayer uses this table to
 * make sure that these processes are terminated when a test run ends.
 *
 * This module is for internal use by Storyplayer. You shouldn't need to call
 * this from your own stories.
 *
 * @return \Prose\ExpectsProcessesTable
 * @throws \Prose\E5xx_ExpectFailed
 */
function expectsProcessesTable()
{
    return new ExpectsProcessesTable(StoryTeller::instance());
}

/**
 * returns the ExpectsRolesTable module
 *
 * This module provides access to Storyplayer's table of active roles in
 * your test environment.
 *
 * This module is for internal use inside Storyplayer. You shouldn't need to
 * use it from your own stories.
 *
 * @return \Prose\ExpectsRolesTable
 * @throws \Prose\E5xx_ExpectFailed
 */
function expectsRolesTable()
{
    return new ExpectsRolesTable(StoryTeller::instance());
}

/**
 * returns the ExpectsRuntimeTable module
 *
 * This module provides access to Storyplayer's internal state, also known
 * as the table of tables.
 *
 * This module is for internal use inside Storyplayer. You shouldn't need to
 * use it from your own stories.
 *
 * @param  string $tableName
 *         which table do we want to test?
 * @return \Prose\ExpectsRuntimeTable
 * @throws \Prose\E5xx_ExpectFailed
 */
function expectsRuntimeTable($tableName)
{
    return new ExpectsRuntimeTable(StoryTeller::instance(), [$tableName]);
}

/**
 * returns the FromShell module
 *
 * This module provides support for running commands via the UNIX shell.
 * These commands will run on the same computer where Storyplayer is running.
 *
 * If you want to run commands on a computer in your test environment, you
 * need the UsingHost module.
 *
 * @return \Prose\ExpectsShell
 * @throws \Prose\E5xx_ExpectFailed
 */
function expectsShell()
{
    return new ExpectsShell(StoryTeller::instance());
}

/**
 * returns the ExpectsSupervisor module
 *
 * This module provides support for working the the Supervisor daemon on
 * a host in your test environment. Supervisor is an increasingly popular
 * solution for looking after network daemons.
 *
 * @param  string $hostId
 *         the ID of the host you want to use Supervisor on
 * @return \Storyplayer\SPv2\Modules\Supervisor\ExpectsSupervisor
 */
function expectsSupervisor($hostId)
{
    return Supervisor::expectsSupervisor($hostId);
}

/**
 * returns the ExpectsUuid module
 *
 * This module adds support for inspecting a universally-unique-ID. To use it,
 * you need to have PHP's UUID extension installed.
 *
 * @return \Prose\ExpectsUuid
 * @throws \Prose\E5xx_ExpectFailed
 */
function expectsUuid()
{
    return new ExpectsUuid(StoryTeller::instance());
}

/**
 * returns the ExpectsZmq module
 *
 * This module provides support for working with ZeroMQ, the no-broker
 * inter-process queuing library.
 *
 * @return \Storyplayer\SPv2\Modules\ZeroMQ\ExpectsZmq
 * @throws \Prose\E5xx_ExpectFailed
 */
function expectsZmq()
{
    return ZeroMQ::expectsZmq();
}

/**
 * returns the ExpectsZmqSocket module
 *
 * This module provides support for testing ZeroMQ sockets
 *
 * @param  \ZMQSocket
 *         the ZMQSocket to test
 * @return \Storyplayer\SPv2\Modules\ZeroMQ\ExpectsZmqSocket
 */
function expectsZmqSocket($zmqSocket)
{
    return ZeroMQ::expectsZmqSocket($zmqSocket);
}

/**
 * iterates over each host in your test environment that has been assigned
 * the given role
 *
 * This helper gives you a way to run any host-aware module across every
 * computer in your test environment that has the role you ask for.
 *
 * For example, if you want to get a table of IP addresses, you can do this:
 *
 *     $ipAddresses = foreachHostWithRole('web-server')->fromHost()->getIpAddress();
 *
 * or, if you wanted to reboot machines, you could do this:
 *
 *     foreachHostWithRole('web-server')->usingHost()->restartHost();
 *
 * This iterator will automatically provide the $hostId parameter to the module
 * you tell it to call, and will collate all return values into an array.
 *
 * Alternatively, you can use a normal PHP foreach() loop:
 *
 *     foreach(hostWithRole('web-server') as $hostId) {
 *         usingHost($hostId)->restartHost();
 *     }
 *
 * @param  string $roleName
 *         the role that you want to work with
 * @return \Storyplayer\SPv2\Modules\Host\ForeachHostWithRole
 */
function foreachHostWithRole($roleName)
{
    return Host::foreachHostWithRole($roleName);
}

/**
 * returns the FromArray module
 *
 * This module contains functions that can be used to manipulate arrays
 *
 * @return \Prose\FromArray
 */
function fromArray()
{
    return new FromArray(StoryTeller::instance());
}

/**
 * returns the FromAws module
 *
 * This module is used internally by Storyplayer to connect the Amazon EC2
 * API, using the official Amazon SDK.
 *
 * If you're writing your own modules for working with EC2, then you should
 * always create your EC2 client using this module.
 *
 * You shouldn't need to call this module from your stories.
 *
 * @return \Prose\FromAws
 */
function fromAws()
{
    return new FromAws(StoryTeller::instance());
}

/**
 * returns the FromBrowser module
 *
 * This module (along with the UsingBrowser module) allows you to control a
 * real web browser from your story.
 *
 * Internally, this module interacts with your chosen browser (you chose by
 * using the --device command-line switch) using the Selenium WebDriver API.
 *
 * We've added a lot of helpful methods you can use to make it both quick
 * and natural to retrieve information from the HTML page that's currently
 * open in the web browser.
 *
 * @return \Storyplayer\SPv2\Modules\Browser\FromBrowser
 */
function fromBrowser()
{
    return Browser::fromBrowser();
}

/**
 * returns the FromCheckpoint module
 *
 * This module is old SPv1 functionality. In practice, you'll want to call
 * getCheckpoint() to retrieve the checkpoint, and then interact with that
 * directly.
 *
 * This module will probably be removed in SPv3.
 *
 * @return \Prose\FromCheckpoint
 */
function fromCheckpoint()
{
    return new FromCheckpoint(StoryTeller::instance());
}

/**
 * returns the FromConfig module
 *
 * This module provides access to Storyplayer's loaded config. This is a
 * combination of your storyplayer.json[.dist] file and additional information
 * about Storyplayer (such as the local computer's IP address) that are
 * detected at runtime.
 *
 * @return \Prose\FromConfig
 */
function fromConfig()
{
    return new FromConfig(StoryTeller::instance());
}

/**
 * returns the FromCurl module
 *
 * This module provides support for making requests using cURL. cURL is the
 * de facto standard library across all major languages for talking to HTTP
 * servers.
 *
 * @return \Prose\FromCurl
 */
function fromCurl()
{
    return new FromCurl(StoryTeller::instance());
}

/**
 * returns the FromEc2 module
 *
 * This module provides support for querying Amazon's EC2.
 *
 * To start an EC2 virtual machine, call usingEc2().
 *
 * @return \Prose\FromEc2
 */
function fromEc2()
{
    return new FromEc2(StoryTeller::instance());
}

/**
 * returns the FromEc2Instance module
 *
 * This module provides support for querying an Amazon EC2 VM.
 *
 * To start an EC2 VM, call usingEc2() first.
 *
 * @param  string $amiId
 *         the AMI ID that you want to work with
 * @return \Prose\FromEc2Instance
 */
function fromEc2Instance($amiId)
{
    return new FromEc2Instance(StoryTeller::instance(), [$amiId]);
}

/**
 * returns the FromEnvironment module
 *
 * This module is here to help you port any SPv1 stories to run under SPv2.
 *
 * In SPv1, we didn't have the concept that Storyplayer, systems-under-test
 * and test environments were separate things in their own right. We had a
 * single combined 'environment' where all configs were lumped together.
 *
 * @deprecated 2.0.0 Use fromStoryplayerConfig(), fromSystemUnderTestConfig() or fromTestEnvironmentConfig() instead
 *
 * @return \Prose\FromEnvironment
 */
function fromEnvironment()
{
    return new FromEnvironment(StoryTeller::instance());
}

/**
 * returns the FromFacebook module
 *
 * This module provides support for querying the Facebook API.
 *
 * It is currently unmaintained.
 *
 * @return \Prose\FromFacebook
 */
function fromFacebook()
{
    return new FromFacebook(StoryTeller::instance());
}

/**
 * returns the FromFile module
 *
 * This is an old SPv1 module that doesn't really do a lot. We'll shortly
 * be introducing a new 'FromFS' module that's much more capable. We'll
 * probably remove the 'FromFile' module in SPv3.
 *
 * @return \Prose\FromFile
 */
function fromFile()
{
    return new FromFile(StoryTeller::instance());
}

/**
 * returns the FromHost module, with its hostId already set to the first
 * host in your test environment config file that has the given role.
 *
 * This module provides support for making your tests work on multiple
 * test environments. Instead of hard-coding hostIds into your stories,
 * use this module to find a host by its assigned role. That way, it doesn't
 * matter how many hosts are in different environments, or if their hostIds
 * are different.
 *
 * This module is normally used for testing read requests via APIs. You
 * should be able to write once, and then read from all hosts to prove
 * that the data was correctly written (and that there are no caching errors).
 *
 * To read from all hosts, you would use:
 *
 *     foreach(hostWithRole($roleName) as $hostId) { ... }
 *
 * @param  string $roleName
 *         the assigned role you're looking for
 * @return \Storyplayer\SPv2\Modules\Host\FromFirstHostWithRole
 */
function fromFirstHostWithRole($roleName)
{
    return Host::fromFirstHostWithRole($roleName);
}

/**
 * returns the FromForm module
 *
 * This module provides support for working with a given form on a HTML page
 * that's open in your browser. To use it, the form must have an 'id'
 * attribute set. Targetting a form with an 'id' helps make your test more
 * robust, especially if there are multiple forms on the same HTML page.
 *
 * Use the UsingBrowser module first to open the HTML page where the form is.
 *
 * @param  string $formId
 *         the 'id' attribute of the HTML form to use
 * @return \Storyplayer\SPv2\Modules\Browser\FromForm
 */
function fromForm($formId)
{
    return Browser::fromForm($formId);
}

/**
 * returns the FromGraphite module
 *
 * This module provides support for retrieving data from Graphite. Graphite
 * is a round-robin database for capturing time-based series of data to be
 * displayed in graphs.
 *
 * You can use SavageD in your test environment to monitor the CPU and
 * memory usage of your system under test, logging the data to Graphite.
 * You can then use this module to make sure that your system under test
 * hasn't used too much CPU or RAM.
 *
 * @return \Prose\FromGraphite
 */
function fromGraphite()
{
    return new FromGraphite(StoryTeller::instance());
}

/**
 * returns the FromHost module
 *
 * This module provides support for running commands on a computer in your
 * test environment - basically for doing anything that's likely to change
 * the state of that computer.
 *
 * In SPv1, it was common to call this module directly from your own stories.
 * In SPv2, you're much more likely to use one of our multi-host modules or
 * helpers (such as usingFirstHostWithRole) so that your stories are as
 * test-environment-independent as possible.
 *
 * @param  string $hostId
 *         the ID of the host to use
 * @return \Storyplayer\SPv2\Modules\Host\FromHost
 */
function fromHost($hostId)
{
    return Host::fromHost($hostId);
}

/**
 * returns the FromHostsTable module
 *
 * This module provides access to Storyplayer's internal list of computers
 * that are running in your test environment.
 *
 * This module is intended for internal use by Storyplayer. You should not
 * need to call this module from your own stories.
 *
 * @return \Storyplayer\SPv2\Modules\Host\FromHostsTable
 */
function fromHostsTable()
{
    return Host::fromHostsTable();
}

/**
 * returns the FromHttp module
 *
 * This module provides support for making GET requests over HTTP.
 *
 * SSL/TLS is fully supported.
 *
 * If you are using self-signed certificates, you will need to set
 * 'moduleSettings.http.validateSsl = false' in your test environment's
 * config file first.
 *
 * To make PUT, POST and DELETE requests, use the UsingHttp module.
 *
 * @return \Storyplayer\SPv2\Modules\HTTP\FromHttp
 */
function fromHttp()
{
    return HTTP::fromHttp();
}

/**
 * returns the FromPDOStatement module
 *
 * This module provides support for retrieving data from a PDO prepared
 * statement after you've executed it.  You can use it in your stories to
 * retrieve the results of SQL queries.
 *
 * To create and run a PDO query, use the UsingPDO and UsingPDOStatement
 * modules.
 *
 * @param  PDOStatement $stmt
 *         the prepared statement used to run an SQL query
 * @return \Prose\FromPDOStatement
 */
function fromPDOStatement(PDOStatement $stmt)
{
    return new FromPDOStatement(StoryTeller::instance(), [$stmt]);
}

/**
 * returns the FromProcessesTable module
 *
 * This module provides access to Storyplayer's internal table of running
 * child processes. Storyplayer uses this table to make sure that these
 * processes are terminated when a test run ends.
 *
 * This module is intended for internal use by Storyplayer. You shouldn't
 * need to call this module from your stories.
 *
 * @return \Prose\FromProcessesTable
 */
function fromProcessesTable()
{
    return new FromProcessesTable(StoryTeller::instance());
}

/**
 * returns the FromRedisConn module
 *
 * This module provides support for retrieving data from a Redis server.
 * Redis is a very popular key/value store (and a whole lot more) - it's a
 * bit like Memcached on steroids :)
 *
 * To make a connection to Redis, use the UsingRedis module first.
 *
 * @param  PredisClient $client
 *         the Redis connection opened with UsingRedis
 * @return \Prose\FromRedisConn
 */
function fromRedisConn(PredisClient $client)
{
    return new FromRedisConn(StoryTeller::instance(), [$client]);
}

/**
 * returns the FromRolesTable module
 *
 * This module provides access to Storyplayer's table of active roles in
 * your test environment.
 *
 * This module is for internal use inside Storyplayer. You shouldn't need to
 * use it from your own stories.
 *
 * @return \Prose\FromRolesTable
 */
function fromRolesTable()
{
    return new FromRolesTable(StoryTeller::instance());
}

/**
 * returns the FromRuntimeTable module
 *
 * The 'runtime table' is Storyplayer's internal table of tables - how it
 * keeps track of what is happening during a test run.
 *
 * This module is intended for internal use only. You shouldn't need to call
 * this module from your own stories.
 *
 * @param  string $tableName
 *         which runtime table do you want?
 * @return \Prose\FromRuntimeTable
 */
function fromRuntimeTable($tableName)
{
    return new FromRuntimeTable(StoryTeller::instance(), [$tableName]);
}

/**
 * returns the FromSauceLabs module
 *
 * At the moment, this module is a placeholder. We hope to add full support
 * for the SauceLabs API soon.
 *
 * You can use the --device switch to run your tests using SauceLabs'
 * platform today. Supported devices start with the prefix 'sl_'.
 *
 * @return \Prose\FromSauceLabs
 */
function fromSauceLabs()
{
    return new FromSauceLabs(StoryTeller::instance());
}

/**
 * returns the FromShell module
 *
 * This module provides support for running commands via the UNIX shell.
 * These commands will run on the same computer where Storyplayer is running.
 *
 * The commands available via this module are for looking up information.
 * They do not make any changes to your computer at all.
 *
 * If you want to run commands on a computer in your test environment, you
 * need the FromHost module.
 *
 * @return \Prose\FromShell
 */
function fromShell()
{
    return new FromShell(StoryTeller::instance());
}

/**
 * returns the FromStoryplayer module
 *
 * This module provides access to Storyplayer's loaded config. This is a
 * combination of your storyplayer.json[.dist] file and additional information
 * about Storyplayer (such as the local computer's IP address) that are
 * detected at runtime.
 *
 * @return \Prose\FromStoryplayer
 */
function fromStoryplayer()
{
    return new FromStoryplayer(StoryTeller::instance());
}


/**
 * returns the FromString module
 *
 * This module provides useful functions for interacting with strings
 *
 * @return FromString
 */
function fromString()
{
    return new FromString(StoryTeller::instance());
}

/**
 * returns the FromSupervisor module
 *
 * This module provides support for querying Supervisor to discover whether
 * an app is running or not. Supervisor is an increasingly popular
 * solution for looking after network daemons.
 *
 * @param  string $hostId
 *         the ID of the host you want to use Supervisor on
 * @return \Storyplayer\SPv2\Modules\Supervisor\FromSupervisor
 */
function fromSupervisor($hostId)
{
    return Supervisor::fromSupervisor($hostId);
}

/**
 * returns the FromSystemUnderTest module
 *
 * This module provides access to the configuration for your chosen
 * system-under-test. You can call this from your stories to get access
 * to any 'appSettings' sections that you have added to your system-under-test
 * config file.
 *
 * The system-under-test config file is an important part of making your
 * stories run against different versions of whatever it is you want to test.
 * Instead of hard-coding app-specific settings in your stories (and then
 * having to use 'if' statements to conditionally interact with the app that
 * you're testing), you can simply add these settings to your
 * system-under-test config files, and load the settings into your stories.
 *
 * @return \Prose\FromSystemUnderTest
 */
function fromSystemUnderTest()
{
    return new FromSystemUnderTest(StoryTeller::instance());
}

/**
 * returns the FromTargetsTable module
 *
 * This module provides access to Storyplayer's internal table of active test
 * environments. It's intended for internal use by Storyplayer.
 *
 * You shouldn't need to call this module from your own stories.
 *
 * @return \Prose\FromTargetsTable
 */
function fromTargetsTable()
{
    return new FromTargetsTable(StoryTeller::instance());
}

/**
 * returns the FromTestEnvironment module
 *
 * This module provides you with access to the configuration for the
 * active test environment.
 *
 * You can use this in your stories to retrieve any 'appSettings' that
 * you've added to your test environment config.
 *
 * 'appSettings' are an important part of making your stories work against
 * multiple test environments. You want all of your end-to-end tests to
 * work against all of your test environments. That way, you can run them
 * in development to prove that new features work, and you can run them in
 * production to prove that your deployment has been successful.
 *
 * You can also use this from any custom modules to retrieve any
 * 'moduleSettings' that have been set in the test environment config.
 *
 * @return \Prose\FromTestEnvironment
 */
function fromTestEnvironment()
{
    return new FromTestEnvironment(StoryTeller::instance());
}

/**
 * returns the FromUsers module
 *
 * This module allows you to retrieve specific user(s) from the test users
 * you loaded with the --users command-line switch. Each user is a plain
 * PHP object created using json_decode().
 *
 * Any changes you make to these users will be saved back to disk when
 * Storyplayer finishes the current test run. You can prevent this by using
 * the --readonly-users command-line switch.
 *
 * @return \Prose\FromUsers
 */
function fromUsers()
{
    return Users::fromUsers();
}

/**
 * returns the FromUuid module
 *
 * This module adds support for inspecting a universally-unique-ID. To use it,
 * you need to have PHP's UUID extension installed.
 *
 * @return \Prose\FromUuid
 */
function fromUuid()
{
    return new FromUuid(StoryTeller::instance());
}

/**
 * returns the FromZmqSocket module
 *
 * This module adds support for receiving data via a ZeroMQ socket.
 *
 * @param  \ZMQSocket $zmqSocket
 *         the ZeroMQ socket you want to receive data from
 * @return \Storyplayer\SPv2\Modules\ZeroMQ\FromZmqSocket
 */
function fromZmqSocket($zmqSocket)
{
    return ZeroMQ::fromZmqSocket($zmqSocket);
}

/**
 * return the Checkpoint object
 *
 * The Checkpoint is a 'data bag' - an object that you can store anything
 * you like it. It's the only way for you to share any data or variables
 * between the different phases of your story.
 *
 * Once you have the checkpoint, simply get and set properties on the object.
 *
 * You can also use the Asserts module on the checkpoint.
 *
 * @return \DataSift\Storyplayer\PlayerLib\Story_Checkpoint
 */
function getCheckpoint()
{
    return StoryTeller::instance()->getCheckpoint();
}

/**
 * shut down the running test device / web browser
 *
 * This function tells Storyplayer to shutdown your chosen web browser
 * (chosen using the --device command-line switch).
 *
 * Storyplayer will normally shut down the test device for you. You should
 * not need to call this from your own stories.
 *
 * @return void
 */
function stopDevice()
{
    return Device::stopDevice();
}

/**
 * returns the UsingBrowser module
 *
 * This module (along with the FromBrowser module) allows you to control a
 * real web browser from your story.
 *
 * Internally, this module interacts with your chosen browser (you chose by
 * using the --device command-line switch) using the Selenium WebDriver API.
 *
 * We've added a lot of helpful methods you can use to make it both quick
 * and natural to control the web browser.
 *
 * @return \Storyplayer\SPv2\Modules\Browser\UsingBrowser
 */
function usingBrowser()
{
    return Browser::usingBrowser();
}

/**
 * returns the UsingCheckpoint module
 *
 * This module is old SPv1 functionality. In practice, you'll want to call
 * getCheckpoint() to retrieve the checkpoint, and then interact with that
 * directly.
 *
 * This module will probably be removed in SPv3.
 *
 * @return \Prose\UsingCheckpoint
 */
function usingCheckpoint()
{
    return new UsingCheckpoint(StoryTeller::instance());
}

/**
 * returns the UsingEc2 module
 *
 * This module provides support for creating, destroying, starting and
 * stopping virtual machines on Amazon EC2. Once the machine has been started,
 * you can interact with it using the FromHost() / UsingHost() module as
 * normal.
 *
 * @return \Prose\UsingEc2
 */
function usingEc2()
{
    return new UsingEc2(StoryTeller::instance());
}

/**
 * returns the UsingEc2Instance module
 *
 * This module provides support for making changes to an Amazon EC2 AMI.
 * You can do the basics such as creating an AMI and editing its configuration.
 *
 * We hope to expand on what this module can do in a future release.
 *
 * If you're looking to start and stop EC2 instances, use a combination of
 * UsingEc2 and UsingHost modules.
 *
 * @param  string $amiId
 *         the AMI ID to work with
 * @return \Prose\UsingEc2Instance
 */
function usingEc2Instance($amiId)
{
    return new UsingEc2Instance(StoryTeller::instance(), [$amiId]);
}

/**
 * returns the UsingFile module
 *
 * This is an old SPv1 module that doesn't really do a lot. We'll shortly
 * be introducing a new 'UsingFS' module that's much more capable. We'll
 * probably remove the 'UsingFile' module in SPv3.
 *
 * @return \Prose\UsingFile
 */
function usingFile()
{
    return new UsingFile(StoryTeller::instance());
}

/**
 * returns the UsingHost module, with its hostId already set to the first
 * host in your test environment config file that has the given role.
 *
 * This module provides support for making your tests work on multiple
 * test environments. Instead of hard-coding hostIds into your stories,
 * use this module to find a host by its assigned role. That way, it doesn't
 * matter how many hosts are in different environments, or if their hostIds
 * are different.
 *
 * This module is normally used for testing write requests via APIs. You
 * should be able to write once, and then read from all hosts to prove
 * that the data was correctly written (and that there are no caching errors).
 *
 * To read from all hosts, you would use:
 *
 *     foreach(hostWithRole($roleName) as $hostId) { ... }
 *
 * @param  string $roleName
 *         the assigned role you're looking for
 * @return \Storyplayer\SPv2\Modules\Host\UsingHost
 */
function usingFirstHostWithRole($roleName)
{
    return Host::usingFirstHostWithRole($roleName);
}

/**
 * returns the UsingForm module
 *
 * This module provides support for working with a given form on a HTML page
 * that's open in your browser. To use it, the form must have an 'id'
 * attribute set. Targetting a form with an 'id' helps make your test more
 * robust, especially if there are multiple forms on the same HTML page.
 *
 * Use the UsingBrowser module first to open the HTML page where the form is.
 *
 * @param  string $formId
 *         the 'id' attribute of the HTML form to use
 * @return \Storyplayer\SPv2\Modules\Browser\UsingForm
 */
function usingForm($formId)
{
    return Browser::usingForm($formId);
}

/**
 * returns the UsingHost module
 *
 * This module provides support for running commands on a computer in your
 * test environment - basically for doing anything that's likely to change
 * the state of that computer.
 *
 * In SPv1, it was common to call this module directly from your own stories.
 * In SPv2, you're much more likely to use one of our multi-host modules or
 * helpers (such as usingFirstHostWithRole) so that your stories are as
 * test-environment-independent as possible.
 *
 * @param  string $hostId
 *         the ID of the host to use
 * @return \Storyplayer\SPv2\Modules\Host\UsingHost
 */
function usingHost($hostId)
{
    return Host::usingHost($hostId);
}

/**
 * returns the UsingHostsTable module
 *
 * This module provides access to Storyplayer's internal list of computers
 * that are running in your test environment.
 *
 * This module is intended for internal use by Storyplayer. You should not
 * need to call this module from your own stories.
 *
 * @return \Storyplayer\SPv2\Modules\Host\UsingHostsTable
 */
function usingHostsTable()
{
    return Host::usingHostsTable();
}

/**
 * returns the UsingHttp module
 *
 * This module provides support for making PUT, POST and DELETE requests
 * over HTTP (basically, any HTTP verb that should change state at the other
 * end).
 *
 * SSL/TLS is fully supported.
 *
 * If you are using self-signed certificates, you will need to set
 * 'moduleSettings.http.validateSsl = false' in your test environment's
 * config file first.
 *
 * To make GET requests, use the FromHttp module.
 *
 * @return \Storyplayer\SPv2\Modules\HTTP\UsingHttp
 */
function usingHttp()
{
    return HTTP::usingHttp(StoryTeller::instance());
}

/**
 * returns the UsingLog module
 *
 * This module provides support for writing to the storyplayer.log. You can
 * use this in your own stories whenever you to need to add an extra log
 * message, for example to make it really clear what is happening.
 *
 * @return \Storyplayer\SPv2\Modules\Host\UsingLog
 */
function usingLog()
{
    return Log::usingLog();
}

/**
 * returns the UsingPDO module
 *
 * This module provides support for opening a PDO connection to a database
 * such as MySQL. PDO is PHP's preferred way of working with SQL databases.
 * Once you have an open connection, use the UsingPDODB module to execute
 * SQL statements.
 *
 * @return \Prose\UsingPDO
 */
function usingPDO()
{
    return new UsingPDO(StoryTeller::instance());
}

/**
 * returns the UsingPDODB module
 *
 * This module provides support for using an open PDO connection. PDO is
 * PHP's preferred way of working with SQL databases such as MySQL. To open
 * a connection, use the UsingPDO module first.
 *
 * @param  \PDO    $db
 *         a PDO connection created by UsingPDO
 * @return \Prose\UsingPDODB
 */
function usingPDODB(PDO $db)
{
    return new UsingPDODB(StoryTeller::instance(), [$db]);
}

/**
 * returns the UsingProcessesTable module
 *
 * This module provides access to Storyplayer's internal table of which
 * child processes are currently running.  Storyplayer uses this table to
 * make sure that these processes are terminated when a test run ends.
 *
 * This module is for internal use by Storyplayer. You shouldn't need to call
 * this from your own stories.
 *
 * @return \Prose\UsingProcessesTable
 */
function usingProcessesTable()
{
    return new UsingProcessesTable(StoryTeller::instance());
}

/**
 * returns the UsingProvisioning module
 *
 * This module provides support for creating a new provisioning definition,
 * for use with the UsingProvisioningDefinition module.
 *
 * In SPv1, stories had to directly manage the provisioning of test
 * environments. In SPv2, this is managed for you through the --targets
 * switch.
 *
 * You shouldn't need to call this module directly from your stories, but
 * we're leaving this functionality in just in case.
 *
 * It's likely that a future SPv2 release will add support for passing a
 * PHP file to the --targets switch. I think this will be easier to use
 * than the current JSON-based approach.
 *
 * @return \Prose\UsingProvisioning
 */
function usingProvisioning()
{
    return new UsingProvisioning(StoryTeller::instance());
}

/**
 * returns the UsingProvisioningDefinition module
 *
 * This module provides support for building up a description of what needs
 * provisioning onto which computer(s) in your test environment.
 *
 * In SPv1, stories had to build up this definition directly. In SPv2, this
 * is managed for you through the --targets switch.
 *
 * You shouldn't need to call this module directly from your stories, but
 * we're leaving this functionality in just in case.
 *
 * @param  ProvisioningDefinition $def
 *         a provisioning definition created by UsingProvisioning
 * @return \Prose\UsingProvisioningDefinition
 */
function usingProvisioningDefinition(ProvisioningDefinition $def)
{
    return new UsingProvisioningDefinition(StoryTeller::instance(), [$def]);
}

/**
 * returns the UsingProvisioningEngine module
 *
 * This module provides support for invoking a supported provisioning
 * engine.
 *
 * Provisioning engines are used to install software into a test environment.
 * In SPv1, stories had to control this directly. In SPv2, this is managed
 * for you through the --targets switch.
 *
 * You shouldn't need to call this module directly from your stories, but
 * we're leaving this functionality in just in case.
 *
 * @param  string $engine
 *         the name of a supported provisioning engine
 * @return \Prose\UsingProvisioningEngine
 */
function usingProvisioningEngine($engine)
{
    return new UsingProvisioningEngine(StoryTeller::instance(), [$engine]);
}

/**
 * returns the UsingRedis module
 *
 * This module provides support for opening connections to a Redis server.
 * Once the connection is open, you can then use the UsingRedisConn module
 * to work with the Redis server.
 *
 * Redis is a very popular key/value store (and a whole lot more) - it's a
 * bit like Memcached on steroids :)
 *
 * @return \Prose\UsingRedis
 */
function usingRedis()
{
    return new UsingRedis(StoryTeller::instance());
}

/**
 * returns the UsingRedisConn module
 *
 * This module provides support for working with a Redis server. Redis is
 * a very popular key/value store (and a whole lot more) - it's a bit like
 * Memcached on steroids :)
 *
 * To make a connection to Redis, use the UsingRedis module first.
 *
 * @param  PredisClient $client
 *         a Redis client created by the UsingRedis module
 * @return \Prose\UsingRedisConn
 */
function usingRedisConn(PredisClient $client)
{
    return new UsingRedisConn(StoryTeller::instance(), [$client]);
}

/**
 * returns the UsingReporting module
 *
 * This module was originally added to SPv1 as a way of logging that a
 * particular Story phase was deliberately empty. In practice, it was rarely
 * used. It will be removed in SPv3.
 *
 * @deprecated 2.1.0 old SPv1 functionality that isn't really needed
 * @return \Prose\UsingReporting
 */
function usingReporting()
{
    return new UsingReporting(StoryTeller::instance());
}

/**
 * returns the UsingRolesTable module
 *
 * This module provides access to Storyplayer's table of active roles in
 * your test environment.
 *
 * This module is for internal use inside Storyplayer. You shouldn't need to
 * use it from your own stories.
 *
 * @return \Prose\UsingRolesTable
 */
function usingRolesTable()
{
    return new UsingRolesTable(StoryTeller::instance());
}

/**
 * returns the UsingRuntimeTable module
 *
 * This module provides access to Storyplayer's internal state, also known
 * as the table of tables.
 *
 * This module is for internal use inside Storyplayer. You shouldn't need to
 * use it from your own stories.
 *
 * @return \Prose\UsingRuntimeTable
 */
function usingRuntimeTable($tableName)
{
    return new UsingRuntimeTable(StoryTeller::instance(), [$tableName]);
}

/**
 * returns the UsingSauceLabs module
 *
 * At the moment, this module is a placeholder. We hope to add full support
 * for the SauceLabs API soon.
 *
 * You can use the --device switch to run your tests using SauceLabs'
 * platform today. Supported devices start with the prefix 'sl_'.
 *
 * @return \Prose\UsingSauceLabs
 */
function usingSauceLabs()
{
    return new UsingSauceLabs(StoryTeller::instance());
}

/**
 * returns the UsingSavageD module
 *
 * This module provides support for controlling the SavageD data-gathering
 * daemon via its REST API. SavageD is a great tool for realtime (to the
 * second) monitoring of processes and memory in your test environment. It
 * was originally built to help load test the DataSift platform.
 *
 * @return \Prose\UsingSavageD
 */
function usingSavageD($hostId)
{
    return new UsingSavageD(StoryTeller::instance(), [$hostId]);
}

/**
 * returns the UsingShell module
 *
 * This module provides support for running commands via the UNIX shell.
 * These commands will run on the same computer where Storyplayer is running.
 *
 * If you want to run commands on a computer in your test environment, you
 * need the UsingHost module.
 *
 * @return \Prose\UsingShell
 */
function usingShell()
{
    return new UsingShell(StoryTeller::instance());
}

/**
 * returns the UsingSupervisor module
 *
 * This module provides support for working the the Supervisor daemon on
 * a host in your test environment. Supervisor is an increasingly popular
 * solution for looking after network daemons.
 *
 * @param  string $hostId
 *         the ID of the host you want to use Supervisor on
 * @return \Storyplayer\SPv2\Modules\Supervisor\UsingSupervisor
 */
function usingSupervisor($hostId)
{
    return Supervisor::usingSupervisor($hostId);
}

/**
 * returns the UsingTargetsTable module
 *
 * This module provides access to Storyplayer's internal table of active
 * test environments. It's intended for internal use by Storyplayer.
 *
 * You shouldn't need to call this module from your own stories.
 *
 * @return \Prose\UsingTargetsTable
 */
function usingTargetsTable()
{
    return new UsingTargetsTable(StoryTeller::instance());
}

/**
 * returns the UsingTimer module
 *
 * This module provides useful helpers for when your story needs to pause
 * or wait for something to happen. If you use these helpers in your stories,
 * you'll get entries in storyplayer.log (and on the --dev console) telling
 * you about these pauses and wait states.
 *
 * You don't get these log messages if you call PHP's sleep() directly from
 * your story.
 *
 * @return \Prose\UsingTimer
 */
function usingTimer()
{
    return new UsingTimer(StoryTeller::instance());
}

/**
 * returns the UsingUsers module
 *
 * This module provides support for working with the test users loaded via
 * the --users switch.
 *
 * Storyplayer uses this module internally to load and save the test users
 * file. You can also load your own files directly from your stories if you
 * need to.
 *
 * @return \Storyplayer\SPv2\Modules\Users\UsingUsers
 */
function usingUsers()
{
    return Users::usingUsers();
}

/**
 * returns the UsingVagrant module
 *
 * This module provides support for working with Vagrant, the CLI tool for
 * managing virtual machines locally and (increasingly) in the cloud
 *
 * A big change in Storyplayer v2 was to move responsibility for creating
 * and destroying test environments out of stories, and into their own
 * configuration files. As a result, it's unlikely you'll need to call this
 * module from your own stories.
 *
 * @return \Prose\UsingVagrant
 */
function usingVagrant()
{
    return new UsingVagrant(StoryTeller::instance());
}

/**
 * returns the UsingYamlFile module
 *
 * This module provides support for working with YAML format files.
 *
 * @param  string $filename
 *         the filename to work with
 * @return \Prose\UsingYamlFile
 */
function usingYamlFile($filename)
{
    return new UsingYamlFile(StoryTeller::instance(), [$filename]);
}

/**
 * returns the UsingZmq module
 *
 * This module provides support for working with ZeroMQ, the no-broker
 * inter-process queuing library.
 *
 * @return \Storyplayer\SPv2\Modules\ZeroMQ\UsingZmq
 */
function usingZmq()
{
    return ZeroMQ::usingZmq();
}

/**
 * returns the UsingZmqContext module
 *
 * This module provides support for creating ZeroMQ sockets
 *
 * @param  \ZMQContext|null $zmqContext
 *         the ZMQContext to use when creating the socket
 *         (leave empty and we'll create a context for you)
 * @return \Storyplayer\SPv2\Modules\ZeroMQ\UsingZmqContext
 */
function usingZmqContext($zmqContext = null, $ioThreads = 1)
{
    return ZeroMQ::usingZmqContext($zmqContext, $ioThreads);
}

/**
 * returns the UsingZmqSocket module
 *
 * This module provides support for sending messages via a ZeroMQ socket
 *
 * @param  \ZMQSocket $zmqSocket
 *         the socket to send on
 * @return \Storyplayer\SPv2\Modules\ZeroMQ\UsingZmqSocket
 */
function usingZmqSocket($zmqSocket)
{
    return ZeroMQ::usingZmqSocket($zmqSocket);
}

// ==================================================================
//
// Iterators go here
//
// ------------------------------------------------------------------

/**
 * iterate over all hosts that match the given role, and return only the first one
 *
 * @param  string $roleName
 *         The role that we want
 *
 * @return Iterator
 *         a hostid that matches the role
 */
function firstHostWithRole($roleName)
{
    $listOfHosts = fromRolesTable()->getDetailsForRole($roleName);
    if (!count($listOfHosts)) {
        throw Exceptions::newActionFailedException(__METHOD__, "unknown role '{$roleName}' or no hosts for that role");
    }

    // what are we doing?
    $log = usingLog()->startAction("for the first host with role '{$roleName}' ... ");

    // we yield a single host ID ...
    $hostId = array_pop($listOfHosts);
    yield($hostId);

    // all done
    $log->endAction();
}

/**
 * iterate over all hosts that match the given role, and return only the last one
 *
 * @param  string $roleName
 *         The role that we want
 *
 * @return Iterator
 *         a hostid that matches the role
 */
function lastHostWithRole($roleName)
{
    $listOfHosts = fromRolesTable()->getDetailsForRole($roleName);
    if (!count($listOfHosts)) {
        throw Exceptions::newActionFailedException(__METHOD__, "unknown role '{$roleName}' or no hosts for that role");
    }

    // what are we doing?
    $log = usingLog()->startAction("for the last host with role '{$roleName}' ... ");

    // we yield a single host ID ...
    $hostId = end($listofHosts);
    yield($hostId);

    // all done
    $log->endAction();
}

/**
 * iterate over all hosts that match the given role
 *
 * @param  string $roleName
 *         The role that we want
 *
 * @return Iterator
 *         a hostId that matches the role
 */
function hostWithRole($roleName)
{
    return Host::getHostsWithRole($roleName);
}
