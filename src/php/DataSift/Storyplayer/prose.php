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

use DataSift\Storyplayer\Prose\AssertsArray;
use DataSift\Storyplayer\Prose\AssertsBoolean;
use DataSift\Storyplayer\Prose\AssertsDouble;
use DataSift\Storyplayer\Prose\AssertsInteger;
use DataSift\Storyplayer\Prose\AssertsObject;
use DataSift\Storyplayer\Prose\AssertsString;
use DataSift\Storyplayer\Prose\CleanupHosts;
use DataSift\Storyplayer\Prose\CleanupProcesses;
use DataSift\Storyplayer\Prose\CleanupRoles;
use DataSift\Storyplayer\Prose\CleanupTargets;
use DataSift\Storyplayer\Prose\ExpectsBrowser;
use DataSift\Storyplayer\Prose\ExpectsEc2Image;
use DataSift\Storyplayer\Prose\ExpectsFailure;
use DataSift\Storyplayer\Prose\ExpectsFirstHostWithRole;
use DataSift\Storyplayer\Prose\ExpectsForm;
use DataSift\Storyplayer\Prose\ExpectsGraphite;
use DataSift\Storyplayer\Prose\ExpectsHost;
use DataSift\Storyplayer\Prose\ExpectsHostsTable;
use DataSift\Storyplayer\Prose\ExpectsHttpResponse;
use DataSift\Storyplayer\Prose\ExpectsIframe;
use DataSift\Storyplayer\Prose\ExpectsProcessesTable;
use DataSift\Storyplayer\Prose\ExpectsRolesTable;
use DataSift\Storyplayer\Prose\ExpectsRuntimeTable;
use DataSift\Storyplayer\Prose\ExpectsShell;
use DataSift\Storyplayer\Prose\ExpectsSupervisor;
use DataSift\Storyplayer\Prose\ExpectsUser;
use DataSift\Storyplayer\Prose\ExpectsUuid;
use DataSift\Storyplayer\Prose\ExpectsZmq;
use DataSift\Storyplayer\Prose\ForeachHostWithRole;
use DataSift\Storyplayer\Prose\FromAws;
use DataSift\Storyplayer\Prose\FromBrowser;
use DataSift\Storyplayer\Prose\FromCheckpoint;
use DataSift\Storyplayer\Prose\FromConfig;
use DataSift\Storyplayer\Prose\FromCurl;
use DataSift\Storyplayer\Prose\FromEc2;
use DataSift\Storyplayer\Prose\FromEc2Instance;
use DataSift\Storyplayer\Prose\FromEnvironment;
use DataSift\Storyplayer\Prose\FromFacebook;
use DataSift\Storyplayer\Prose\FromFile;
use DataSift\Storyplayer\Prose\FromFirstHostWithRole;
use DataSift\Storyplayer\Prose\FromForm;
use DataSift\Storyplayer\Prose\FromGraphite;
use DataSift\Storyplayer\Prose\FromHost;
use DataSift\Storyplayer\Prose\FromHostsTable;
use DataSift\Storyplayer\Prose\FromHttp;
use DataSift\Storyplayer\Prose\FromIframe;
use DataSift\Storyplayer\Prose\FromPDOStatement;
use DataSift\Storyplayer\Prose\FromProcessesTable;
use DataSift\Storyplayer\Prose\FromRedisConn;
use DataSift\Storyplayer\Prose\FromRolesTable;
use DataSift\Storyplayer\Prose\FromRuntimeTable;
use DataSift\Storyplayer\Prose\FromRuntimeTableForTargetEnvironment;
use DataSift\Storyplayer\Prose\FromSauceLabs;
use DataSift\Storyplayer\Prose\FromShell;
use DataSift\Storyplayer\Prose\FromSupervisor;
use DataSift\Storyplayer\Prose\FromSystemUnderTest;
use DataSift\Storyplayer\Prose\FromTargetsTable;
use DataSift\Storyplayer\Prose\FromTestEnvironment;
use DataSift\Storyplayer\Prose\FromUuid;
use DataSift\Storyplayer\Prose\UsingBrowser;
use DataSift\Storyplayer\Prose\UsingCheckpoint;
use DataSift\Storyplayer\Prose\UsingEc2;
use DataSift\Storyplayer\Prose\UsingEc2Instance;
use DataSift\Storyplayer\Prose\UsingFacebookGraphApi;
use DataSift\Storyplayer\Prose\UsingFile;
use DataSift\Storyplayer\Prose\UsingFirstHostWithRole;
use DataSift\Storyplayer\Prose\UsingForm;
use DataSift\Storyplayer\Prose\UsingHornet;
use DataSift\Storyplayer\Prose\UsingHost;
use DataSift\Storyplayer\Prose\UsingHostsTable;
use DataSift\Storyplayer\Prose\UsingHttp;
use DataSift\Storyplayer\Prose\UsingIframe;
use DataSift\Storyplayer\Prose\UsingLog;
use DataSift\Storyplayer\Prose\UsingMysql;
use DataSift\Storyplayer\Prose\UsingPDO;
use DataSift\Storyplayer\Prose\UsingPDODB;
use DataSift\Storyplayer\Prose\UsingProcessesTable;
use DataSift\Storyplayer\Prose\UsingProvisioning;
use DataSift\Storyplayer\Prose\UsingProvisioningDefinition;
use DataSift\Storyplayer\Prose\UsingProvisioningEngine;
use DataSift\Storyplayer\Prose\UsingRedis;
use DataSift\Storyplayer\Prose\UsingRedisConn;
use DataSift\Storyplayer\Prose\UsingReporting;
use DataSift\Storyplayer\Prose\UsingRolesTable;
use DataSift\Storyplayer\Prose\UsingRuntimeTable;
use DataSift\Storyplayer\Prose\UsingRuntimeTableForTargetEnvironment;
use DataSift\Storyplayer\Prose\UsingSauceLabs;
use DataSift\Storyplayer\Prose\UsingSavageD;
use DataSift\Storyplayer\Prose\UsingShell;
use DataSift\Storyplayer\Prose\UsingSupervisor;
use DataSift\Storyplayer\Prose\UsingTargetsTable;
use DataSift\Storyplayer\Prose\UsingTimer;
use DataSift\Storyplayer\Prose\UsingVagrant;
use DataSift\Storyplayer\Prose\UsingYamlFile;
use DataSift\Storyplayer\Prose\UsingZmq;
use DataSift\Storyplayer\Prose\UsingZookeeper;

/**
 * [assertsArray description]
 * @param  [type] $expected [description]
 * @return [type]           [description]
 */
function assertsArray($expected)
{
	return new AssertsArray(StoryTeller::instance(), [$expected]);
}

function assertsBoolean($expected)
{
	return new AssertsBoolean(StoryTeller::instance(), [$expected]);
}

function assertsDouble($expected)
{
	return new AssertsDouble(StoryTeller::instance(), [$expected]);
}

function assertsInteger($expected)
{
	return new AssertsInteger(StoryTeller::instance(), [$expected]);
}

function assertsObject($expected)
{
	return new AssertsObject(StoryTeller::instance(), [$expected]);
}

function assertsString($expected)
{
	return new AssertsString(StoryTeller::instance(), [$expected]);
}

function cleanupHosts($key)
{
	return new CleanupHosts(StoryTeller::instance(), [$key]);
}

function cleanupProcesses($key)
{
	return new CleanupProcesses(StoryTeller::instance(), [$key]);
}

function cleanupRoles($key)
{
	return new CleanupRoles(StoryTeller::instance(), [$key]);
}

function cleanupTargets($key)
{
	return new CleanupTargets(StoryTeller::instance(), [$key]);
}

function expectsBrowser()
{
	return new ExpectsBrowser(StoryTeller::instance());
}

function expectsEc2Image($amiId)
{
	return new ExpectsEc2Image(StoryTeller::instance(), [$amiId]);
}

function expectsFailure()
{
	return new ExpectsFailure(StoryTeller::instance());
}

function expectsFirstHostWithRole($role)
{
	return new ExpectsFirstHostWithRole(StoryTeller::instance(), [$role]);
}

function expectsForm($formId)
{
	return new ExpectsForm(StoryTeller::instance(), [$formId]);
}

function expectsGraphite()
{
	return new ExpectsGraphite(StoryTeller::instance());
}

function expectsHost($hostname)
{
	return new ExpectsHost(StoryTeller::instance(), [$hostname]);
}

function expectsHostsTable()
{
	return new ExpectsHostsTable(StoryTeller::instance());
}

function expectsHttpResponse(HttpClientResponse $httpResponse)
{
	return new ExpectsHttpResponse(StoryTeller::instance(), [$httpResponse]);
}

function expectsProcessesTable()
{
	return new ExpectsProcessesTable(StoryTeller::instance());
}

function expectsRolesTable()
{
	return new ExpectsRolesTable(StoryTeller::instance());
}

function expectsRuntimeTable()
{
	return new ExpectsRuntimeTable(StoryTeller::instance());
}

function expectsShell()
{
	return new ExpectsShell(StoryTeller::instance());
}

function expectsSupervisor($hostname)
{
	return new ExpectsSupervisor(StoryTeller::instance(), [$hostname]);
}

function expectsUuid()
{
	return new ExpectsUuid(StoryTeller::instance());
}

function expectsZmq()
{
	return new ExpectsZmq(StoryTeller::instance());
}
function foreachHostWithRole($roleName)
{
	return new ForeachHostWithRole(StoryTeller::instance(), [$roleName]);
}

function fromAws()
{
	return new FromAws(StoryTeller::instance());
}

function fromBrowser()
{
	return new FromBrowser(StoryTeller::instance());
}

function fromCheckpoint()
{
	return new FromCheckpoint(StoryTeller::instance());
}

function fromConfig()
{
	return new FromConfig(StoryTeller::instance());
}

function fromCurl()
{
	return new FromCurl(StoryTeller::instance());
}

function fromEc2()
{
	return new FromEc2(StoryTeller::instance());
}

function fromEc2Instance($amiId)
{
	return new FromEc2Instance(StoryTeller::instance(), [$amiId]);
}

function fromEnvironment()
{
	return new FromEnvironment(StoryTeller::instance());
}

function fromFacebook()
{
	return new FromFacebook(StoryTeller::instance());
}

function fromFile()
{
	return new FromFile(StoryTeller::instance());
}

function fromFirstHostWithRole($roleName)
{
	return new FromFirstHostWithRole(StoryTeller::instance(), [$roleName]);
}

function fromForm($formId)
{
	return new FromForm(StoryTeller::instance(), [$formId]);
}

function fromGraphite()
{
	return new FromGraphite(StoryTeller::instance());
}

function fromHost($hostName)
{
	return new FromHost(StoryTeller::instance(), [$hostName]);
}

function fromHostsTable()
{
	return new FromHostsTable(StoryTeller::instance());
}

function fromHttp()
{
	return new FromHttp(StoryTeller::instance());
}

function fromPDOStatement(PDOStatement $stmt)
{
	return new FromPDOStatement(StoryTeller::instance(), [$stmt]);
}

function fromProcessesTable()
{
	return new FromProcessesTable(StoryTeller::instance());
}

function fromRedisConn(PredisClient $client)
{
	return new FromRedisConn(StoryTeller::instance(), [$client]);
}

function fromRolesTable()
{
	return new FromRolesTable(StoryTeller::instance());
}

function fromRuntimeTable()
{
	return new FromRuntimeTable(StoryTeller::instance());
}

function fromRuntimeTableForTargetEnvironment()
{
	return new FromRuntimeTableForTargetEnvironment(StoryTeller::instance());
}

function fromSauceLabs()
{
	return new FromSauceLabs(StoryTeller::instance());
}

function fromShell()
{
	return new FromShell(StoryTeller::instance());
}

function fromSupervisor($hostName)
{
	return new FromSupervisor(StoryTeller::instance());
}

function fromSystemUnderTest()
{
	return new FromSystemUnderTest(StoryTeller::instance());
}

function fromTargetsTable()
{
	return new FromTargetsTable(StoryTeller::instance());
}

function fromTestEnvironment()
{
	return new FromTestEnvironment(StoryTeller::instance());
}

function fromUuid()
{
	return new FromUuid(StoryTeller::instance());
}

function getCheckpoint()
{
	return StoryTeller::instance()->getCheckpoint();
}

function stopDevice()
{
	return StoryTeller::instance()->stopDevice();
}

function usingBrowser()
{
	return new UsingBrowser(StoryTeller::instance());
}

function usingCheckpoint()
{
	return new UsingCheckpoint(StoryTeller::instance());
}

function usingEc2()
{
	return new UsingEc2(StoryTeller::instance());
}

function usingEc2Instance($amiId)
{
	return new UsingEc2Instance(StoryTeller::instance());
}

function usingFile()
{
	return new UsingFile(StoryTeller::instance());
}

function usingFirstHostWithRole($roleName)
{
	return new UsingFirstHostWithRole(StoryTeller::instance(), [$roleName]);
}

function usingForm($formId)
{
	return new UsingForm(StoryTeller::instance(), [$formId]);
}

function usingHost($hostName)
{
	return new UsingHost(StoryTeller::instance(), [$hostName]);
}

function usingHostsTable()
{
	return new UsingHostsTable(StoryTeller::instance());
}

function usingHttp()
{
	return new UsingHttp(StoryTeller::instance());
}

function usingLog()
{
	return new UsingLog(StoryTeller::instance());
}

function usingPDO()
{
	return new UsingPDO(StoryTeller::instance());
}

function usingPDODB(PDO $db)
{
	return new UsingPDODB(StoryTeller::instance(), [$db]);
}

function usingProcessesTable()
{
	return new UsingProcessesTable(StoryTeller::instance());
}

function usingProvisioning()
{
	return new UsingProvisioning(StoryTeller::instance());
}

function usingProvisioningDefinition(ProvisioningDefinition $def)
{
	return new UsingProvisioningDefinition(StoryTeller::instance(), [$def]);
}

function usingProvisioningEngine($engine)
{
	return new UsingProvisioningEngine(StoryTeller::instance(), [$engine]);
}

function usingRedis()
{
	return new UsingRedis(StoryTeller::instance());
}

function usingRedisConn(PredisClient $client)
{
	return new UsingRedisConn(StoryTeller::instance(), [$client]);
}

function usingReporting()
{
	return new UsingReporting(StoryTeller::instance());
}

function usingRolesTable()
{
	return new UsingRolesTable(StoryTeller::instance());
}

function usingRuntimeTable()
{
	return new UsingRolesTable(StoryTeller::instance());
}

function usingRuntimeTableForTargetEnvironment()
{
	return new UsingRuntimeTableForTargetEnvironment(StoryTeller::instance());
}

function usingSauceLabs()
{
	return new UsingSauceLabs(StoryTeller::instance());
}

function usingSavageD()
{
	return new UsingSavageD(StoryTeller::instance());
}

function usingShell()
{
	return new UsingShell(StoryTeller::instance());
}

function usingSupervisor($hostName)
{
	return new UsingSupervisor(StoryTeller::instance(), [$hostName]);
}

function usingTargetsTable()
{
	return new UsingTargetsTable(StoryTeller::instance());
}

function usingTimer()
{
	return new UsingTimer(StoryTeller::instance());
}

function usingVagrant()
{
	return new UsingVagrant(StoryTeller::instance());
}

function usingYamlFile($filename)
{
	return new UsingYamlFile(StoryTeller::instance(), [$filename]);
}

function usingZmq()
{
	return new UsingZmq(StoryTeller::instance());
}
