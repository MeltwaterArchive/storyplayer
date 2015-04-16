<?php

/**
 * Copyright (c) 2011-present Mediasift Ltd
 * Copyright (c) 2015-present Ganbaro Digital Ltd
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
 * @package   Storyplayer/DefinitionLib
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\DefinitionLib;

use Storyplayer\TestEnvironments\HostAdapter;
use Storyplayer\TestEnvironments\HostAdapterValidator;
use Storyplayer\TestEnvironments\OsAdapter;
use Storyplayer\TestEnvironments\OsAdapterValidator;

use DataSift\Storyplayer\DefinitionLib\TestEnvironment_RolesValidator;

use DataSift\Stone\ObjectLib\BaseObject;

/**
 * Represents a host defined in the TestEnvironment
 *
 * @category  Libraries
 * @package   Storyplayer/DefinitionLib
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class TestEnvironment_HostDefinition
{
    /**
     * the settings that apps may be interested in
     *
     * @var BaseObject
     */
    protected $storySettings;

    public function __construct(TestEnvironment_GroupDefinition $parentGroup, $hostId)
    {
        $this->setParentGroup($parentGroup);
        $this->setHostId($hostId);

        // start with an empty set of story settings
        $this->storySettings = new BaseObject;
    }

    // ==================================================================
    //
    // Parent group support
    //
    // ------------------------------------------------------------------

    /**
     * the group that this host belongs to
     *
     * @var TestEnvironment_GroupDefinition
     */
    protected $parentGroup;


    public function getParentGroup()
    {
        return $this->parentGroup;
    }

    public function setParentGroup(TestEnvironment_GroupDefinition $parentGroup)
    {
        $this->parentGroup = $parentGroup;
    }

    // ==================================================================
    //
    // hostId support
    //
    // ------------------------------------------------------------------

    /**
     * the ID assigned to this host (it's complicated)
     * @var string
     */
    protected $hostId;

    /**
     * what is the ID of this host?
     *
     * host IDs are the names that Storyplayer (and stories you write) will
     * use to refer to a host. They may or may not be the same as the host's
     * hostname.
     *
     * @return string
     */
    public function getHostId()
    {
        return $this->hostId;
    }

    /**
     * tell this host what its host ID is
     *
     * NOTES:
     *
     * * host IDs are normally validated in the group definition
     *
     * @param string $hostId
     *        the host ID to use for this host
     */
    public function setHostId($hostId)
    {
        $this->hostId = $hostId;
    }

    // ==================================================================
    //
    // Operating system support
    //
    // ------------------------------------------------------------------

    /**
     * what operating system is running on this host?
     *
     * @return OsAdapter
     */
    public function getOperatingSystem()
    {
        return $this->osAdapter;
    }

    /**
     * tell this host what operating system is running on this host
     *
     * @param OsAdapter $osAdapter
     *        the adapter to use for the relevant operating system
     */
    public function setOperatingSystem(OsAdapter $osAdapter)
    {
        // make sure the operating system is compatible with this group

        // remember the adapter
        $this->osAdapter = $osAdapter;

        // fluent interface
        return $this;
    }

    public function getOperatingSystemValidator()
    {
        return $this->osAdapterValidator;
    }

    public function setOperatingSystemValidator(OsAdapterValidator $osAdapterValidator)
    {
        // remember the validator for the adapter
        $this->osAdapterValidator = $osAdapterValidator;

        // fluent interface
        return $this;
    }

    // ==================================================================
    //
    // Host adapter support
    //
    // ------------------------------------------------------------------

    /**
     * the adapter to use when interacting with this host
     *
     * @var HostAdapter
     */
    protected $hostAdapter;

    /**
     * how do we interact with this host?
     *
     * @return HostAdapter
     */
    public function getHostAdapter()
    {
        return $this->hostAdapter;
    }

    /**
     * tell us how to interact with this host
     *
     * @param HostAdapter $hostAdapter
     *        the adapter we should use
     */
    public function setHostAdapter(HostAdapter $hostAdapter)
    {
        // we need to validate first
        $validator = $this->getParentGroup()->getHostAdapterValidator();
        if (!$validator->validate($hostAdapter)) {
            throw new E4xx_IncompatibleHostAdapter(
                $this->getTestEnvironmentName(),
                $this->getGroupId(),
                $this->getHostId(),
                $hostManager,
                $this->getGroupAdapter()
            );
        }

        // remember the adapter
        $this->hostAdapter = $hostAdapter;

        // fluent interface
        return $this;
    }

    // ==================================================================
    //
    // Roles support
    //
    // ------------------------------------------------------------------

    /**
     * a list of the roles that this host supports
     * @var array<string>
     */
    protected $roles = [];

    /**
     * what roles does this host support?
     *
     * @return array<string>
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * tell this host what roles it supports
     *
     * @param array<string> $roles
     *        a list of the roles that it supports
     */
    public function setRoles($roles)
    {
        // make sure we have what we expect
        $rolesValidator = new TestEnvironment_RolesValidator($this);
        $rolesValidator->validate($roles);

        // remember the roles
        $this->roles = $roles;

        // all done
        // fluent interface support
        return $this;
    }

    // ==================================================================
    //
    // Settings support goes here
    //
    // ------------------------------------------------------------------

    /**
     * return all of the story settings that this host supports
     *
     * @return BaseObject
     */
    public function getStorySettings()
    {
        return $this->storySettings;
    }

    /**
     * tell this host what story settings it supports
     *
     * @param array|object|null $rawSettings
     *        the settings to set
     */
    public function setStorySettings($rawSettings)
    {
        // just in case we've been called more than once
        $this->storySettings = new BaseObject;

        // convert to our BaseObject, which comes with all sorts of
        // funky helpers
        $this->storySettings->mergeFrom($rawSettings);

        // all done
        // flient interface support
        return $this;
    }

    // ==================================================================
    //
    // Helpers go here
    //
    // ------------------------------------------------------------------

    function getGroupAdapter()
    {
        return $this->parentGroup->getGroupAdapter();
    }

    function getGroupId()
    {
        return $this->parentGroup->getGroupId();
    }

    function getTestEnvironmentName()
    {
        return $this->parentGroup->getTestEnvironmentName();
    }
}
