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

use Storyplayer\SPv3\TestEnvironments\GroupAdapter;
use Storyplayer\SPv3\TestEnvironments\HostAdapter;
use Storyplayer\SPv3\TestEnvironments\ProvisioningAdapter;

use DataSift\Stone\ObjectLib\BaseObject;

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
     * the test environment that we belong to
     * @var TestEnvironment_Definition
     */
    protected $parentEnv;

    /**
     * which group are we?
     * @var int|string
     */
    protected $groupId;

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

    public function __construct(TestEnvironment_Definition $parent, $groupId, GroupAdapter $groupAdapter)
    {
        $this->parentEnv = $parent;
        $this->groupId   = $groupId;
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
     * @param HostAdapter $hostAdapter
     *        the plugin for this kind of host
     * @return TestEnvironment_HostDefinition
     *         the empty host definition, for you to complete
     */
    public function newHost($hostId, HostAdapter $hostAdapter)
    {
        // make sure we're happy with the hostId
        $hostIdValidator = new TestEnvironment_HostIdValidator($this);
        $hostIdValidator->validate($hostId);

        // create the new host and send it back
        $this->hosts[$hostId] = $hostAdapter->newHostDefinition($this, $hostId);
        $this->hosts[$hostId]->setHostAdapter($hostAdapter);
        return $this->hosts[$hostId];
    }

    // ==================================================================
    //
    // Group adapter features go here
    //
    // ------------------------------------------------------------------

    /**
     * get the plugin we use to start and stop this test environment
     *
     * @return GroupAdapter
     */
    public function getGroupAdapter()
    {
        return $this->groupAdapter;
    }

    /**
     * tell us which plugin to use to start and stop this test environment
     *
     * @param GroupAdapter $groupAdapter
     *        the adapter to use for this group
     *
     * @return TestEnvironment_GroupDefinition
     */
    public function setGroupAdapter(GroupAdapter $groupAdapter)
    {
        $this->groupAdapter = $groupAdapter;

        // fluent interface support
        return $this;
    }

    /**
     * throws an exception if we don't have a valid group adapter to use
     *
     * @return void
     */
    protected function requireGroupAdapter()
    {
        if (!$this->groupAdapter instanceof GroupAdapter) {
            throw new E4xx_NeedGroupAdapter($this->getTestEnvironmentName(), $this->getGroupId());
        }
    }

    /**
     * what should we use to validate a host adapter that is being added
     * to one of the hosts in this group?
     *
     * @return \Storyplayer\TestEnvironments\HostAdapterValidator
     */
    public function getHostAdapterValidator()
    {
        $this->requireGroupAdapter();
        return $this->groupAdapter->getHostAdapterValidator();
    }

    /**
     * what type of group are we?
     *
     * this is the name of the class (without namespace) that our group
     * adapter uses
     *
     * @return string
     */
    public function getGroupType()
    {
        $this->requireGroupAdapter();
        return $this->groupAdapter->getType();
    }

    /**
     * which folder contains all of our supporting files?
     *
     * @return string
     */
    public function getBaseFolder()
    {
        $this->requireGroupAdapter();
        return $this->groupAdapter->getBaseFolder();
    }

    // ==================================================================
    //
    // Provisioning support goes here
    //
    // ------------------------------------------------------------------

    /**
     * a list of provisioning adapters to use for this group
     *
     * @var array<ProvisioningAdapter>
     */
    protected $provisioningAdapters;

    /**
     * how should we provision this environment?
     *
     * @return array<ProvisioningAdapters>
     */
    public function getProvisioningAdapters()
    {
        return $this->provisioningAdapters;
    }

    /**
     * add a provisioning adapter to our list
     *
     * our list can have as many provisioning adapters as you would like
     * they are executed in the order that you add them
     *
     * @param ProvisioningAdapter $adapter
     *        the provisioning adapter to add
     */
    public function addProvisioningAdapter(ProvisioningAdapter $adapter)
    {
        $this->provisioningAdapters[] = $adapter;
        return $this;
    }

    /**
     * do we have any provisioning adapters?
     *
     * @return boolean
     */
    public function hasProvisioningAdapters()
    {
        if (count($this->provisioningAdapters) === 0) {
            return false;
        }

        return true;
    }

    // ==================================================================
    //
    // Host support goes here
    //
    // ------------------------------------------------------------------

    /**
     * what hosts exist in this group?
     *
     * @return array<TestEnvironment_HostDefinition>
     */
    public function getHosts()
    {
        return $this->hosts;
    }

    // ==================================================================
    //
    // SPv2.0-style config support goes here
    //
    // ------------------------------------------------------------------

    /**
     * return our hosts, as SPv2.0-style config tree
     *
     * @return BaseObject
     */
    public function getHostsAsConfig()
    {
        // our return value
        $retval = new BaseObject;

        // ask our hosts to convert themselves
        foreach ($this->hosts as $hostDef) {
            $hostConf = $hostDef->getHostAsConfig();
            $retval->{$hostConf->name} = $hostConf;
        }

        // all done
        return $retval;
    }

    /**
     * return our first provisioning adapter, as SPv2.0-style config tree
     * @return BaseObject
     */
    public function getProvisioningAsConfig()
    {
        // special case
        if (count($this->provisioningAdapters) === 0) {
            return null;
        }

        // ask our first adapter to yield up its config
        $firstAdapter = first($this->provisioningAdapters);
        return $firstAdapter->getAsConfig();
    }

    // ==================================================================
    //
    // Helpers go here
    //
    // ------------------------------------------------------------------

    /**
     * what is our group ID?
     *
     * @return int|string
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * which test environment do we belong to?
     *
     * @return string
     */
    public function getTestEnvironmentName()
    {
        return $this->parentEnv->getName();
    }
}
