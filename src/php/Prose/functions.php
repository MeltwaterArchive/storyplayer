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
use Prose\ExpectsUser;
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
use Prose\UsingVagrant;
use Prose\UsingYamlFile;
use Prose\UsingZmq;
use Prose\UsingZookeeper;

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

function expectsHost($hostId)
{
	return new ExpectsHost(StoryTeller::instance(), [$hostId]);
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

function expectsSupervisor($hostId)
{
	return new ExpectsSupervisor(StoryTeller::instance(), [$hostId]);
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

function fromHost($hostId)
{
	return new FromHost(StoryTeller::instance(), [$hostId]);
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

function fromSupervisor($hostId)
{
	return new FromSupervisor(StoryTeller::instance(),[$hostId]);
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

function usingHost($hostId)
{
	return new UsingHost(StoryTeller::instance(), [$hostId]);
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

function usingSupervisor($hostId)
{
	return new UsingSupervisor(StoryTeller::instance(), [$hostId]);
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
