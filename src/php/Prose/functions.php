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

use Prose\AssertsArray;
use Prose\AssertsBoolean;
use Prose\AssertsDouble;
use Prose\AssertsInteger;
use Prose\AssertsObject;
use Prose\AssertsString;
use Prose\CleanupHosts;
use Prose\CleanupProcesses;
use Prose\CleanupRoles;
use Prose\CleanupTargets;
use Prose\ExpectsBrowser;
use Prose\ExpectsEc2Image;
use Prose\ExpectsFailure;
use Prose\ExpectsFirstHostWithRole;
use Prose\ExpectsForm;
use Prose\ExpectsGraphite;
use Prose\ExpectsHost;
use Prose\ExpectsHostsTable;
use Prose\ExpectsHttpResponse;
use Prose\ExpectsIframe;
use Prose\ExpectsProcessesTable;
use Prose\ExpectsRolesTable;
use Prose\ExpectsRuntimeTable;
use Prose\ExpectsShell;
use Prose\ExpectsSupervisor;
use Prose\ExpectsUuid;
use Prose\ExpectsZmq;
use Prose\ForeachHostWithRole;
use Prose\FromAws;
use Prose\FromBrowser;
use Prose\FromCheckpoint;
use Prose\FromConfig;
use Prose\FromCurl;
use Prose\FromEc2;
use Prose\FromEc2Instance;
use Prose\FromEnvironment;
use Prose\FromFacebook;
use Prose\FromFile;
use Prose\FromFirstHostWithRole;
use Prose\FromForm;
use Prose\FromGraphite;
use Prose\FromHost;
use Prose\FromHostsTable;
use Prose\FromHttp;
use Prose\FromIframe;
use Prose\FromPDOStatement;
use Prose\FromProcessesTable;
use Prose\FromRedisConn;
use Prose\FromRolesTable;
use Prose\FromRuntimeTable;
use Prose\FromRuntimeTableForTargetEnvironment;
use Prose\FromSauceLabs;
use Prose\FromShell;
use Prose\FromSupervisor;
use Prose\FromSystemUnderTest;
use Prose\FromTargetsTable;
use Prose\FromTestEnvironment;
use Prose\FromUsers;
use Prose\FromUuid;
use Prose\UsingBrowser;
use Prose\UsingCheckpoint;
use Prose\UsingEc2;
use Prose\UsingEc2Instance;
use Prose\UsingFacebookGraphApi;
use Prose\UsingFile;
use Prose\UsingFirstHostWithRole;
use Prose\UsingForm;
use Prose\UsingHornet;
use Prose\UsingHost;
use Prose\UsingHostsTable;
use Prose\UsingHttp;
use Prose\UsingIframe;
use Prose\UsingLog;
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
use Prose\UsingRuntimeTableForTargetEnvironment;
use Prose\UsingSauceLabs;
use Prose\UsingSavageD;
use Prose\UsingShell;
use Prose\UsingSupervisor;
use Prose\UsingTargetsTable;
use Prose\UsingTimer;
use Prose\UsingUsers;
use Prose\UsingVagrant;
use Prose\UsingYamlFile;
use Prose\UsingZmq;
use Prose\UsingZookeeper;

/**
 * check a PHP array and its contents
 *
 * @param  array $actual
 *         the array to be checked
 *
 * @return \Prose\AssertsArray
 *         array inspector object
 */
function assertsArray($actual)
{
    return new AssertsArray(StoryTeller::instance(), [$actual]);
}

/**
 * check a PHP boolean
 *
 * @param  boolean $actual
 *         the variable to be check
 *
 * @return \Prose\AssertsBoolean
 *         boolean inspector object
 */
function assertsBoolean($actual)
{
    return new AssertsBoolean(StoryTeller::instance(), [$actual]);
}

/**
 * check a PHP double
 *
 * @param  double $actual
 *         the variable to be checked
 *
 * @return \Prose\AssertsDouble
 *         double inspector object
 */
function assertsDouble($actual)
{
    return new AssertsDouble(StoryTeller::instance(), [$actual]);
}

/**
 * check a PHP integer
 *
 * @param  integer $actual
 *         the variable to be checked
 *
 * @return \Prose\AssertsInteger
 *         integer inspector object
 */
function assertsInteger($actual)
{
    return new AssertsInteger(StoryTeller::instance(), [$actual]);
}

/**
 * check a PHP object
 *
 * @param  object $actual
 *         the variable to be checked
 *
 * @return \Prose\AssertsObject
 *         object inspector object
 */
function assertsObject($actual)
{
    return new AssertsObject(StoryTeller::instance(), [$actual]);
}

/**
 * check a PHP string
 *
 * @param  string $actual
 *         the variable to be checked
 *
 * @return \Prose\AssertsString
 *         string inspector object
 */
function assertsString($actual)
{
    return new AssertsString(StoryTeller::instance(), [$actual]);
}

/**
 * clean up the hosts table
 *
 * NOTES:
 *
 * - the hosts table is part of the runtime config
 * - Storyplayer calls this when shutting down
 *
 * @param  string $key
 *         the name of the hosts table
 *         (i.e. $runtimeConfig->$key)
 *
 * @return \Prose\CleanupHosts
 */
function cleanupHosts($key)
{
    return new CleanupHosts(StoryTeller::instance(), [$key]);
}

/**
 * clean up the processes table
 *
 * NOTES:
 *
 * - the processes table is part of the runtime config
 * - Storyplayer calls this when shutting down
 *
 * @param  string $key
 *         the name of the processes table
 *         (i.e. $runtimeConfig->$key)
 *
 * @return \Prose\CleanupProcesses
 */
function cleanupProcesses($key)
{
    return new CleanupProcesses(StoryTeller::instance(), [$key]);
}

/**
 * clean up the roles table
 *
 * NOTES:
 *
 * - the roles table is part of the runtime config
 * - Storyplayer calls this when shutting down
 *
 * @param  string $key
 *         the name of the roles table
 *         (i.e. $runtimeConfig->$key)
 *
 * @return \Prose\CleanupRoles
 */
function cleanupRoles($key)
{
    return new CleanupRoles(StoryTeller::instance(), [$key]);
}

/**
 * clean up the targets table
 *
 * NOTES:
 *
 * - the targets table is part of the runtime config
 * - Storyplayer calls this when shutting down
 *
 * @param  string $key
 *         the name of the targets table
 *         (i.e. $runtimeConfig->$key)
 *
 * @return \Prose\CleanupTargets
 */
function cleanupTargets($key)
{
    return new CleanupTargets(StoryTeller::instance(), [$key]);
}

/**
 * check that a condition is met inside the web browser
 *
 * @return \Prose\ExpectsBrowser
 */
function expectsBrowser()
{
    return new ExpectsBrowser(StoryTeller::instance());
}

/**
 * check that an EC2 image meets a given condition
 *
 * @param  string $amiId
 *         the ID of the EC2 AMI
 * @return \Prose\ExpectsEc2Image
 */
function expectsEc2Image($amiId)
{
    return new ExpectsEc2Image(StoryTeller::instance(), [$amiId]);
}

/**
 * expect a callable to fail with an exception
 *
 * @return \Prose\ExpectsFailure
 */
function expectsFailure()
{
    return new ExpectsFailure(StoryTeller::instance());
}

/**
 * check that the first test environment machine that has a given role
 * meets a given condition
 *
 * @param  string $role
 *         the role to search for
 * @return \Prose\ExpectsFirstHostWithRole
 */
function expectsFirstHostWithRole($role)
{
    return new ExpectsFirstHostWithRole(StoryTeller::instance(), [$role]);
}

/**
 * check that a given HTML form in the web browser meets a given condition
 *
 * @param  string $formId
 *         the 'id' attribute of the HTML form to check
 * @return \Prose\ExpectsForm
 */
function expectsForm($formId)
{
    return new ExpectsForm(StoryTeller::instance(), [$formId]);
}

/**
 * check that data in Graphite meets a given condition
 *
 * @return \Prose\ExpectsGraphite
 */
function expectsGraphite()
{
    return new ExpectsGraphite(StoryTeller::instance());
}

/**
 * check that a given machine in the test environment meets a given condition
 *
 * @param  string $hostId
 *         the ID of the host to check
 * @return \Prose\ExpectsHost
 */
function expectsHost($hostId)
{
    return new ExpectsHost(StoryTeller::instance(), [$hostId]);
}

/**
 * check that Storyplayer's internal list of active hosts meets a given
 * condition
 *
 * @return \Prose\ExpectsHostsTable
 */
function expectsHostsTable()
{
    return new ExpectsHostsTable(StoryTeller::instance());
}

/**
 * check that the reply from a HTTP request meets a given condition
 *
 * @param  HttpClientResponse $httpResponse
 *         the HTTP response to be checked
 * @return \Prose\ExpectsHttpResponse
 */
function expectsHttpResponse(HttpClientResponse $httpResponse)
{
    return new ExpectsHttpResponse(StoryTeller::instance(), [$httpResponse]);
}

/**
 * check that Storyplayer's internal list of running processes meets a
 * given condition
 *
 * @return \Prose\ExpectsProcessesTable
 */
function expectsProcessesTable()
{
    return new ExpectsProcessesTable(StoryTeller::instance());
}

/**
 * check that Storyplayer's internal list of test environment roles meets
 * a given condition
 *
 * @return \Prose\ExpectsRolesTable
 */
function expectsRolesTable()
{
    return new ExpectsRolesTable(StoryTeller::instance());
}

/**
 * check that Storyplayer's internal table of tables meets a given condition
 *
 * @return \Prose\ExpectsRuntimeTable
 */
function expectsRuntimeTable()
{
    return new ExpectsRuntimeTable(StoryTeller::instance());
}

/**
 * use a UNIX shell to check that a given condition is met
 *
 * @return \Expects\Shell
 */
function expectsShell()
{
    return new ExpectsShell(StoryTeller::instance());
}

/**
 * use Supervisor to check that a given condition is met
 *
 * @param  string $hostId
 *         the ID of the host in your test environment which you want to check
 * @return \Prose\ExpectsSupervisor
 */
function expectsSupervisor($hostId)
{
    return new ExpectsSupervisor(StoryTeller::instance(), [$hostId]);
}

/**
 * check a universally-unique identifier
 *
 * @return \Prose\ExpectsUuid
 */
function expectsUuid()
{
    return new ExpectsUuid(StoryTeller::instance());
}

/**
 * check a ZMQ connection
 *
 * @return \Prose\ExpectsZmq
 */
function expectsZmq()
{
    return new ExpectsZmq(StoryTeller::instance());
}

/**
 * run a Storyplayer module against every host in your test environment that
 * has a given role
 *
 * @param  string $roleName
 *         the name of the role to search for
 * @return \Prose\ForeachHostWithRole
 */
function foreachHostWithRole($roleName)
{
    return new ForeachHostWithRole(StoryTeller::instance(), [$roleName]);
}

/**
 * retrieve information from Amazon Web Services
 *
 * @return \Prose\FromAws
 */
function fromAws()
{
    return new FromAws(StoryTeller::instance());
}

/**
 * retrieve information from the web browser
 *
 * @return \Prose\FromBrowser
 */
function fromBrowser()
{
    return new FromBrowser(StoryTeller::instance());
}

/**
 * retrieve data from the checkpoint
 *
 * @return \Prose\FromCheckpoint
 */
function fromCheckpoint()
{
    return new FromCheckpoint(StoryTeller::instance());
}

/**
 * retrieve data from Storyplayer's active config
 *
 * @return \Prose\FromConfig
 */
function fromConfig()
{
    return new FromConfig(StoryTeller::instance());
}

/**
 * retrieve information by making a cURL request
 *
 * @return \Prose\FromCurl
 */
function fromCurl()
{
    return new FromCurl(StoryTeller::instance());
}

/**
 * retrieve information from Amazon EC2
 *
 * @return \Prose\FromEc2
 */
function fromEc2()
{
    return new FromEc2(StoryTeller::instance());
}

/**
 * retrieve information from an Amazon EC2 instance
 *
 * @param  string $amiId
 *         the ID of the AMI to get information from
 * @return \Prose\FromEc2Instance
 */
function fromEc2Instance($amiId)
{
    return new FromEc2Instance(StoryTeller::instance(), [$amiId]);
}

/*
 * WE DO NOT PROVIDE THE FROMENVIRONMENT() CALL
 *
 * $st->fromEnvironment() is a Storyplayer v1 feature. We still support
 * it to make it easier for DataSift to port their tests to SPv2
 *
 * no new story should use it, and it will completely disappear in SPv3
 *
function fromEnvironment()
{
    return new FromEnvironment(StoryTeller::instance());
}
*/

/**
 * retrieve information from Facebook
 *
 * @deprecated 2.0.0 unmaintained code?
 * @return \Prose\FromFacebook
 */
function fromFacebook()
{
    return new FromFacebook(StoryTeller::instance());
}

/**
 * retrieve information from a file
 *
 * @return \Prose\FromFile
 */
function fromFile()
{
    return new FromFile(StoryTeller::instance());
}

/**
 * retrieve information from the first machine in your test environment that
 * has a given role
 *
 * @param  string $roleName
 *         the name of the role to search for
 * @return \Prose\FromFirstHostWithRole
 */
function fromFirstHostWithRole($roleName)
{
    return new FromFirstHostWithRole(StoryTeller::instance(), [$roleName]);
}

/**
 * retrieve information from a given HTML form
 *
 * @param  string $formId
 *         the 'id' attribute of the form to use
 * @return \Prose\FromForm
 */
function fromForm($formId)
{
    return new FromForm(StoryTeller::instance(), [$formId]);
}

/**
 * retrieve information from Graphite
 *
 * @return \Prose\FromGraphite
 */
function fromGraphite()
{
    return new FromGraphite(StoryTeller::instance());
}

/**
 * retrieve information from a machine in your test environment
 *
 * @param  string $hostId
 *         the ID of the machine to look at
 * @return \Prose\FromHost
 */
function fromHost($hostId)
{
    return new FromHost(StoryTeller::instance(), [$hostId]);
}

/**
 * retrieve information from Storyplayer's internal table of running
 * machines
 *
 * @return \Prose\FromHostsTable
 */
function fromHostsTable()
{
    return new FromHostsTable(StoryTeller::instance());
}

/**
 * retrieve information using a HTTP call
 *
 * @return \Prose\FromHttp
 */
function fromHttp()
{
    return new FromHttp(StoryTeller::instance());
}

/**
 * retrieve information from a PDOStatement object
 *
 * @param  PDOStatement $stmt
 *         the PDO statement to inspect
 * @return \Prose\FromPDOStatement
 */
function fromPDOStatement(PDOStatement $stmt)
{
    return new FromPDOStatement(StoryTeller::instance(), [$stmt]);
}

/**
 * retrieve information from Storyplayer's internal list of running processes
 *
 * @return \Prose\FromProcessesTable
 */
function fromProcessesTable()
{
    return new FromProcessesTable(StoryTeller::instance());
}

/**
 * retrieve information from Redis
 *
 * @param  PredisClient $client
 *         the Redis client created by usingRedis()->connect()
 * @return \Prose\FromRedisConn
 */
function fromRedisConn(PredisClient $client)
{
    return new FromRedisConn(StoryTeller::instance(), [$client]);
}

/**
 * retrieve information from Storyplayer's internal list of active roles
 * in your test environment
 *
 * @return \Prose\FromRolesTable
 */
function fromRolesTable()
{
    return new FromRolesTable(StoryTeller::instance());
}

/**
 * retrieve information from Storyplayer's internal table of tables
 *
 * @return \Prose\FromRuntimeTable
 */
function fromRuntimeTable()
{
    return new FromRuntimeTable(StoryTeller::instance());
}

/**
 * retrieve information from Storyplayer's internal table for your current
 * test environment
 *
 * @return \Prose\FromRuntimeTableForTargetEnvironment
 */
function fromRuntimeTableForTargetEnvironment()
{
    return new FromRuntimeTableForTargetEnvironment(StoryTeller::instance());
}

/**
 * placeholder. does nothing useful yet.
 *
 * @return \Prose\FromSauceLabs
 */
function fromSauceLabs()
{
    return new FromSauceLabs(StoryTeller::instance());
}

/**
 * retrieve information using the UNIX shell
 *
 * @return \Prose\FromShell
 */
function fromShell()
{
    return new FromShell(StoryTeller::instance());
}

/**
 * retrieve information from Supervisor running on a given machine in your
 * test environment
 *
 * @param  string $hostId
 *         the ID of the host to look inside
 * @return \Prose\FromSupervisor
 */
function fromSupervisor($hostId)
{
    return new FromSupervisor(StoryTeller::instance(),[$hostId]);
}

/**
 * retrieve information from your system under test config
 *
 * @return \Prose\FromSystemUnderTest
 */
function fromSystemUnderTest()
{
    return new FromSystemUnderTest(StoryTeller::instance());
}

/**
 * retrieve information from Storyplayer's internal list of active test
 * environments
 *
 * NOTE:
 *
 * - if you have used the --persist-target / -P switch, there can be multiple
 *   test environments active at once
 *
 * @return \Prose\FromTargetsTable
 */
function fromTargetsTable()
{
    return new FromTargetsTable(StoryTeller::instance());
}

/**
 * retrieve information from your test environment config
 *
 * @return \Prose\FromTestEnvironment
 */
function fromTestEnvironment()
{
    return new FromTestEnvironment(StoryTeller::instance());
}

/**
 * retrieve information from the test users you loaded with the --users
 * switch
 *
 * @return \Prose\FromUsers
 */
function fromUsers()
{
    return new FromUsers(StoryTeller::instance());
}

/**
 * retrieve information from a universally-unique ID
 *
 * @return \Prose\FromUuid
 */
function fromUuid()
{
    return new FromUuid(StoryTeller::instance());
}

/**
 * get the checkpoint object
 *
 * @return \DataSift\Storyplayer\PlayerLib\Story_Checkpoint
 */
function getCheckpoint()
{
    return StoryTeller::instance()->getCheckpoint();
}

/**
 * stop the currently running test device
 *
 * NOTES:
 *
 * - this is mostly here for Storyplayer's internal use. If you have to call
 *   this from your story, chances are something is wrong
 *
 * @return void
 */
function stopDevice()
{
    return StoryTeller::instance()->stopDevice();
}

/**
 * do something in the web browser
 *
 * @return \Prose\UsingBrowser
 */
function usingBrowser()
{
    return new UsingBrowser(StoryTeller::instance());
}

/**
 * do something to the checkpoint object
 *
 * @return \Prose\Checkpoint
 */
function usingCheckpoint()
{
    return new UsingCheckpoint(StoryTeller::instance());
}

/**
 * do something on Amazon EC2
 *
 * @return \Prose\UsingEc2
 */
function usingEc2()
{
    return new UsingEc2(StoryTeller::instance());
}

/**
 * do something to an Amazon EC2 instance
 *
 * @param  string $amiId
 *         the ID of the AMI you want to use
 * @return \Prose\UsingEc2Instance
 */
function usingEc2Instance($amiId)
{
    return new UsingEc2Instance(StoryTeller::instance());
}

/**
 * do something to a file
 *
 * @return \Prose\UsingFile
 */
function usingFile()
{
    return new UsingFile(StoryTeller::instance());
}

/**
 * do something to the first machine in your test environment that has a
 * given role
 *
 * @param  string $roleName
 *         the name of the role to search for
 * @return \Prose\UsingFirstHostWithRole
 */
function usingFirstHostWithRole($roleName)
{
    return new UsingFirstHostWithRole(StoryTeller::instance(), [$roleName]);
}

/**
 * do something inside a HTML form
 *
 * @param  string $formId
 *         the 'id' attribute of the HTML form to use
 * @return \Prose\UsingForm
 */
function usingForm($formId)
{
    return new UsingForm(StoryTeller::instance(), [$formId]);
}

/**
 * do something to a given machine in your test environment
 *
 * @param  string $hostId
 *         the ID of the machine to use
 * @return \Prose\UsingHost
 */
function usingHost($hostId)
{
    return new UsingHost(StoryTeller::instance(), [$hostId]);
}

/**
 * do something to Storyplayer's internal table of active machines
 *
 * @return \Prose\UsingHostsTable
 */
function usingHostsTable()
{
    return new UsingHostsTable(StoryTeller::instance());
}

/**
 * do something by making a HTTP request
 *
 * NOTES:
 *
 * - use fromHttp()->get() to make GET requests
 *
 * @return \Prose\UsingHttp
 */
function usingHttp()
{
    return new UsingHttp(StoryTeller::instance());
}

/**
 * write something to Storyplayer's log file
 *
 * @return \Prose\UsingLog
 */
function usingLog()
{
    return new UsingLog(StoryTeller::instance());
}

/**
 * do something to a database server using PHP's PDO feature
 *
 * @return \Prose\UsingPDO
 */
function usingPDO()
{
    return new UsingPDO(StoryTeller::instance());
}

/**
 * do something to a given database
 *
 * @param  PDO    $db
 *         the database that you're connected to
 * @return \Prose\UsingPDODB
 */
function usingPDODB(PDO $db)
{
    return new UsingPDODB(StoryTeller::instance(), [$db]);
}

/**
 * do something to Storyplayer's internal table of running processes
 *
 * @return \Prose\UsingProcessesTable
 */
function usingProcessesTable()
{
    return new UsingProcessesTable(StoryTeller::instance());
}

/**
 * create a new provisioning definition
 *
 * @return \Prose\UsingProvisioning
 */
function usingProvisioning()
{
    return new UsingProvisioning(StoryTeller::instance());
}

/**
 * build up a provisioning definition
 *
 * @param  ProvisioningDefinition $def
 *         the definition created by usingProvisioning()->createDefinition()
 * @return \Prose\UsingProvisioningDefinition
 */
function usingProvisioningDefinition(ProvisioningDefinition $def)
{
    return new UsingProvisioningDefinition(StoryTeller::instance(), [$def]);
}

/**
 * do something using one of our supported provisioning engines
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
 * do something to a Redis server
 *
 * @return \Prose\UsingRedis
 */
function usingRedis()
{
    return new UsingRedis(StoryTeller::instance());
}

/**
 * do something to a Redis server that we've connected to
 *
 * @param  PredisClient $client
 *         the connection created by usingRedis()->connect()
 * @return \Prose\UsingRedisConn
 */
function usingRedisConn(PredisClient $client)
{
    return new UsingRedisConn(StoryTeller::instance(), [$client]);
}

/*
 * INTERNAL USE ONLY
 *
 * WAS USED IN SPv1 WHEN STORIES WANTED A NICE WAY TO SKIP A PHASE
 * HARDLY USED. WILL PROBABLY REMOVE SOON
 *
function usingReporting()
{
    return new UsingReporting(StoryTeller::instance());
}
*/

function usingRolesTable()
{
    return new UsingRolesTable(StoryTeller::instance());
}

/**
 * do something to Storyplayer's internal table of tables
 *
 * @return \Prose\UsingRuntimeTable
 */
function usingRuntimeTable()
{
    return new UsingRolesTable(StoryTeller::instance());
}

/**
 * do something to Storyplayer's internal table for your current test
 * environment
 *
 * @return \Prose\UsingRuntimeTableForTargetEnvironment
 */
function usingRuntimeTableForTargetEnvironment()
{
    return new UsingRuntimeTableForTargetEnvironment(StoryTeller::instance());
}

/**
 * placeholder. currently does nothing
 *
 * @return \Prose\UsingSauceLabs
 */
function usingSauceLabs()
{
    return new UsingSauceLabs(StoryTeller::instance());
}

/**
 * do something to the SavageD process monitoring daemon
 *
 * @return \Prose\UsingSavageD
 */
function usingSavageD()
{
    return new UsingSavageD(StoryTeller::instance());
}

/**
 * do something using the UNIX shell
 *
 * @return \Prose\UsingShell
 */
function usingShell()
{
    return new UsingShell(StoryTeller::instance());
}

/**
 * do something to Supervisor running on a given machine in your test
 * environment
 *
 * @param  string $hostId
 *         the ID of the machine you want to use
 * @return \Prose\UsingSupervisor
 */
function usingSupervisor($hostId)
{
    return new UsingSupervisor(StoryTeller::instance(), [$hostId]);
}

/**
 * do something to Storyplayer's internal table of active test environments
 *
 * @return \Prose\UsingTargetsTable
 */
function usingTargetsTable()
{
    return new UsingTargetsTable(StoryTeller::instance());
}

/**
 * do something that needs timing
 *
 * @return \Prose\UsingTimer
 */
function usingTimer()
{
    return new UsingTimer(StoryTeller::instance());
}

/**
 * do something to the list of test users you loaded using the --users
 * switch
 *
 * @return \Prose\UsingUsers
 */
function usingUsers()
{
    return new UsingUsers(StoryTeller::instance());
}

/**
 * do something to one or more Vagrant VMs
 *
 * @return \Prose\UsingVagrant
 */
function usingVagrant()
{
    return new UsingVagrant(StoryTeller::instance());
}

/**
 * do something to a YAML file
 *
 * @param  string $filename
 *         the name of the file to use
 * @return \Prose\UsingYamlFile
 */
function usingYamlFile($filename)
{
    return new UsingYamlFile(StoryTeller::instance(), [$filename]);
}

/**
 * do something using ZeroMQ
 *
 * @return \Prose\UsingZmq
 */
function usingZmq()
{
    return new UsingZmq(StoryTeller::instance());
}
