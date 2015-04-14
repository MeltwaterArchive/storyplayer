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

use Storyplayer\HostManagers\HostManager;
use Storyplayer\HostManagers\HostManagerValidator;
use Storyplayer\OsAdapters\OsAdapter;
use Storyplayer\OsAdapters\OsAdapterValidator;

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
     * the group that this host belongs to
     *
     * @var TestEnvironment_GroupDefinition
     */
    protected $parentGroup;

    /**
     * the ID assigned to this host (it's complicated)
     * @var string
     */
    protected $hostId;

    public function __construct(TestEnvironment_GroupDefinition $parentGroup, $hostId)
    {
        $this->setParentGroup($parentGroup);
        $this->setHostId($hostId);
    }

    // ==================================================================
    //
    // Parent group support
    //
    // ------------------------------------------------------------------

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

    public function getHostId()
    {
        return $this->hostId;
    }

    public function setHostId($hostId)
    {
        $this->hostId = $hostId;
    }

    // ==================================================================
    //
    // Operating system support
    //
    // ------------------------------------------------------------------

    public function getOperatingSystem()
    {
        return $this->osAdapter;
    }

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
    // Host manager support
    //
    // ------------------------------------------------------------------

    public function getHostManager()
    {
        return $this->hostManager;
    }

    public function setHostManager(HostManager $hostManager)
    {
        // remember the adapter
        $this->hostManager = $hostManager;

        // fluent interface
        return $this;
    }
}
