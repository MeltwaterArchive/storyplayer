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

use DataSift\Stone\ObjectLib\BaseObject;

/**
 * manipulate the internal roles table
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class UsingRolesTable extends Prose
{
    /**
     * entryKey
     * The key that this table interacts with in the RuntimeConfig
     *
     * @var string
     */
    protected $entryKey = "roles";

    /**
     * addHost
     *
     * @param object $hostDetails
     *        Details about the host to add to the role
     * @param string $roleName
     *        Role name to add
     *
     * @return void
     */
    public function addHostToRole($hostDetails, $roleName)
    {
        // shorthand
        $hostId = $hostDetails->hostId;

        // what are we doing?
        $log = usingLog()->startAction("add host '{$hostId}' to role '{$roleName}'");

        // do we have this role already?
        $hasRole = true;
        $role = fromRuntimeTable($this->entryKey)->getDetails($roleName);
        if ($role === null) {
            $role = [];
            $hasRole = false;
        }

        // does this host already have this role?
        if (in_array($hostId, $role)) {
            // all done
            $log->endAction();
            return;
        }

        // if we get here, the host needs adding to the role
        $role[] = $hostId;

        // add it
        if ($hasRole) {
            usingRuntimeTable($this->entryKey)->updateItem($roleName, $role);
        }
        else {
            usingRuntimeTable($this->entryKey)->addItem($roleName, $role);
        }

        // all done
        $log->endAction();
    }

    /**
     * removeRole
     *
     * @param string $hostId
     *        ID of the host to remove
     * @param string $roleName
     *        Role name to remove
     *
     * @return void
     */
    public function removeHostFromRole($hostId, $roleName)
    {
        // what are we doing?
        $log = usingLog()->startAction("remove host '{$hostId}' from '{$roleName}'");

        // let's see what we have
        $role = fromRuntimeTable($this->entryKey)->getDetails($roleName);
        if (!is_array($role) || empty($role)) {
            // no such role
            $log->endAction();
            return;
        }

        if (!in_array($hostId, $role)) {
            // host does not have this role
            $log->endAction();
            return;
        }

        // remove the host from this role
        $role = $this->filterHostIdFromRole($hostId, $role);

        // remove it
        usingRuntimeTable($this->entryKey)->addItem($roleName, $role);

        // all done
        $log->endAction();
    }

    /**
     * remove a host from all of our known roles
     *
     * after calling this, $hostId will not be found by any of our 'xxWithRole'
     * iterators
     *
     * @param  string $hostId
     *         the ID of the host to forget
     * @return void
     */
    public function removeHostFromAllRoles($hostId)
    {
        // what are we doing?
        $log = usingLog()->startAction("remove host '{$hostId}' from all roles");

        // get the full table of roles
        $roles = fromRuntimeTable($this->entryKey)->getTable();
        foreach ($roles as $roleName => $hosts) {
            // skip any empty roles
            if (!is_array($hosts) || empty($hosts)) {
                continue;
            }

            // skip any roles where the hostId is not present
            if (!in_array($hostId, $hosts)) {
                continue;
            }

            // if we get here, then the host needs removing from this role
            $role = $this->filterHostIdFromRole($hostId, $hosts);

            // save the role
            if (empty($role)) {
                usingRuntimeTable($this->entryKey)->removeItem($roleName);
            }
            else {
                usingRuntimeTable($this->entryKey)->updateItem($roleName, $role);
            }
        }

        // all done
        $log->endAction();
    }

    /**
     * remove a hostId from a role
     *
     * @param  string $hostId
     *         the hostId to be removed
     * @param  array<string> $role
     *         the role that we need to edit
     *
     * @return array<string>
     *         the (possibly) updated role
     */
    protected function filterHostIdFromRole($hostId, $role)
    {
        $retval = array_filter($role, function($input) use ($hostId) {
            if ($input === $hostId) {
                return false;
            }

            return true;
        });

        return $retval;
    }

    /**
     * empty out the table
     *
     * @return void
     */
    public function emptyTable()
    {
        // what are we doing?
        $log = usingLog()->startAction("empty the roles table completely");

        // remove it
        usingRuntimeTable($this->entryKey)->removeTable();

        // all done
        $log->endAction();
    }
}
