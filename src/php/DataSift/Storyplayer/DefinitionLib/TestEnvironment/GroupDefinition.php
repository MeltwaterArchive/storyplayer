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

use Storyplayer\GroupAdapters\GroupAdapter;

/**
 * Represents a group of hosts in the test environment
 *
 * @category  Libraries
 * @package   Storyplayer/DefinitionLib
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class TestEnvironment_GroupDefinition
{
    /**
     * the code that understands what's special about this type of group
     * @var GroupAdapter
     */
    protected $groupAdapter;

    /**
     * a list of all of the hosts in this group
     * @var array<TestEnvironmentHost>
     */
    protected $hosts = array();

    public function __construct(GroupAdapter $groupAdapter)
    {
        $this->setGroupAdapter($groupAdapter);
    }

    // ==================================================================
    //
    // The interface to use in config files goes here
    //
    // ------------------------------------------------------------------

    /**
     * start the definition of a host in this group
     *
     * @param string $hostId
     *        the ID of this host
     * @return TestEnvironmentHost
     *         the empty host definition, for you to complete
     */
    public function addHost($hostId)
    {
        // make sure 'hostId' is a string
        if (!is_string($hostId)) {
            throw new E4xx_IllegalHostId("hostId must be a string, " . gettype($hostId) . " given");
        }
        $this->hosts[$hostId] = new TestEnvironment_HostDefinition($this, $hostId);
        return $this->hosts[$hostId];
    }

    // ==================================================================
    //
    // Group adapter features go here
    //
    // ------------------------------------------------------------------

    public function getGroupAdapter()
    {
        return $this->groupAdapter;
    }

    public function setGroupAdapter(GroupAdapter $groupAdapter)
    {
        $this->groupAdapter = $groupAdapter;
    }

    public function getOsAdapterValidator()
    {
        return $this->groupAdapter->getOsAdapterValidator();
    }

    public function getHostManagerValidator()
    {
        return $this->groupAdapter->getHostAdapterValidator();
    }
}
