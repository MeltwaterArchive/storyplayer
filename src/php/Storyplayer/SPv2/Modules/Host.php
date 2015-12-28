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

namespace Storyplayer\SPv2\Modules;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use Iterator;
use Storyplayer\SPv2\Modules\Host\CleanupHosts;
use Storyplayer\SPv2\Modules\Host\ExpectsFirstHostWithRole;
use Storyplayer\SPv2\Modules\Host\ExpectsHost;
use Storyplayer\SPv2\Modules\Host\FromFirstHostWithRole;
use Storyplayer\SPv2\Modules\Host\FromHost;
use Storyplayer\SPv2\Modules\Host\FromHostsTable;
use Storyplayer\SPv2\Modules\Host\UsingFirstHostWithRole;
use Storyplayer\SPv2\Modules\Host\UsingHost;
use Storyplayer\SPv2\Modules\Host\UsingHostsTable;

class Host
{
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
     * @return CleanupHosts
     */
    public static function cleanupHosts($key)
    {
        return new CleanupHosts(StoryTeller::instance(), [$key]);
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
     * @return ExpectsFirstHostWithRole
     */
    public static function expectsFirstHostWithRole($roleName)
    {
        return new ExpectsFirstHostWithRole(StoryTeller::instance(), [$roleName]);
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
     * @return ExpectsHost
     */
    public static function expectsHost($hostId)
    {
        return new ExpectsHost(StoryTeller::instance(), [$hostId]);
    }

    /**
     * returns the ExpectsHost module
     *
     * This module provides support for checking on something on the computer
     * where Storyplayer is running.  If the check fails, an exception is
     * thrown for you.
     *
     * @return ExpectsHost
     */
    public static function expectsLocalhost()
    {
        return new ExpectsHost(StoryTeller::instance(), ['localhost']);
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
     * @return ExpectsHostsTable
     */
    public static function expectsHostsTable()
    {
        return new ExpectsHostsTable(StoryTeller::instance());
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
     * @return ForeachHostWithRole
     */
    public static function foreachHostWithRole($roleName)
    {
        return new ForeachHostWithRole(StoryTeller::instance(), [$roleName]);
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
     * @return FromFirstHostWithRole
     */
    public static function fromFirstHostWithRole($roleName)
    {
        return new FromFirstHostWithRole(StoryTeller::instance(), [$roleName]);
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
     * @return FromHost
     */
    public static function fromHost($hostId)
    {
        return new FromHost(StoryTeller::instance(), [$hostId]);
    }

    /**
     * returns the FromHost module
     *
     * This module provides support for getting information about the computer
     * where Storyplayer is running.
     *
     * @return FromHost
     */
    public static function fromLocalhost()
    {
        return new FromHost(StoryTeller::instance(), ['localhost']);
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
     * @return FromHostsTable
     */
    public static function fromHostsTable()
    {
        return new FromHostsTable(StoryTeller::instance());
    }

    /**
     * get all host IDs that match the given role
     *
     * @param  string $roleName
     *         The role that we want
     *
     * @return Iterator
     *         a hostId that matches the role
     */
    public static function getHostsWithRole($roleName)
    {
        // special case
        if ($roleName instanceof StoryTeller) {
            throw Exceptions::newActionFailedException(__METHOD__, "first param to hostWithRole() is no longer \$st");
        }

        $listOfHosts = fromRolesTable()->getDetailsForRole($roleName);
        if (!count($listOfHosts)) {
            throw Exceptions::newActionFailedException(__METHOD__, "unknown role '{$roleName}' or no hosts for that role");
        }

        // what are we doing?
        $log = usingLog()->startAction("for each host with role '{$roleName}' ... ");

        foreach ($listOfHosts as $hostId) {
            yield($hostId);
        }

        // all done
        $log->endAction();
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
     * @return UsingHost
     */
    public static function usingFirstHostWithRole($roleName)
    {
        return new UsingFirstHostWithRole(StoryTeller::instance(), [$roleName]);
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
     * @return UsingHost
     */
    public static function onHost($hostId)
    {
        return new UsingHost(StoryTeller::instance(), [$hostId]);
    }

    /**
     * returns the UsingHost module
     *
     * This module provides support for running commands on a computer in your
     * test environment - basically for doing anything that's likely to change
     * the state of that computer.
     *
     * @return UsingHost
     */
    public static function onLocalhost()
    {
        return new UsingHost(StoryTeller::instance(), ['localhost']);
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
     * @return UsingHostsTable
     */
    public static function usingHostsTable()
    {
        return new UsingHostsTable(StoryTeller::instance());
    }
}
