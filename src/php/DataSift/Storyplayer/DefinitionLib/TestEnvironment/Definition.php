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

use Storyplayer\TestEnvironments\GroupAdapter;
use DataSift\Stone\ObjectLib\BaseObject;

/**
 * Represents the TestEnvironment that is being created / destroyed
 *
 * @category  Libraries
 * @package   Storyplayer/DefinitionLib
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class TestEnvironment_Definition
{
    /**
     * the name of this test environment
     *
     * @var string
     */
    protected $name;

    /**
     * create a new test environment definition
     *
     * @param string $name
     *        the name to assign to this test environment
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * what is the name of this test environment?
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * set the name of this test environment
     *
     * @param string $name
     *        the new name for this test environment
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * a list of all of the groups of hosts in our test environment
     * @var array<TestEnvironmentGroup>
     */
    protected $groups = array();

    /**
     * create a new group of hosts
     *
     * @param  GroupAdapter $groupAdapter
     *         the adapter for this group
     *
     * @return TestEnvironmentGroup
     *         a new and empty group, ready for you to define
     */
    public function newGroup(GroupAdapter $groupAdapter)
    {
        $this->groups[] = new TestEnvironment_GroupDefinition($this, count($this->groups) + 1, $groupAdapter);
        return end($this->groups);
    }

    // ==================================================================
    //
    // Settings go here
    //
    // ------------------------------------------------------------------

    /**
     * any module settings for this test environment
     *
     * these settings apply to any host in any group in this environment
     *
     * @var null|object
     */
    protected $moduleSettings = null;

    /**
     * get the current module settings for this test environment
     *
     * @return null|object
     */
    public function getModuleSettings()
    {
        return $this->moduleSettings;
    }

    /**
     * tell us the module settings you want to apply to this test environment
     *
     * @param object $newSettings
     *        the settings to apply
     */
    public function setModuleSettings($newSettings)
    {
        $this->moduleSettings = new BaseObject;
        $this->moduleSettings->mergeFrom($newSettings);

        return $this;
    }

    /**
     * do we have any module settings?
     *
     * @return boolean
     */
    public function hasModuleSettings()
    {
        if ($this->moduleSettings === null) {
            return false;
        }

        return true;
    }

    // ==================================================================
    //
    // Support for merging in from the system under test
    //
    // ------------------------------------------------------------------

    public function mergeSystemUnderTestConfig($sutConfig)
    {
        // do we have anything to merge?
        if (!$sutConfig->hasData('roles')) {
            // nothing to merge
            return;
        }

        // get the list of roles
        $sutRoles = $sutConfig->getData('roles');

        // we need to merge in the role params
        foreach ($sutRoles as $sutRole) {
            foreach ($this->groups as $groupDef) {
                foreach ($groupDef->getHosts() as $hostDef) {
                    if ($hostDef->hasRole($sutRole->role)) {
                        $hostDef->addProvisioningParams($sutRole->params);
                    }
                }
            }
        }
    }

    // ==================================================================
    //
    // Support for dot.notation.support
    //
    // ------------------------------------------------------------------

    /**
     * what does our definition look like, as a config structure?
     *
     * IMPORTANT:
     *
     * we make sure that the data we return is a read-only copy of our
     * definition. At the time of writing, we're not sure if other parts
     * of SPv2 will break as a result.
     *
     * @return BaseObject
     */
    public function getConfig()
    {
        // the config that we will return to the caller
        $retval = new BaseObject;

        // first things first ... who are we?
        $retval->name = $this->getName();

        // let's get the groups built
        $retval->groups = [];
        foreach ($this->groups as $groupDef) {
            $groupConfig = new BaseObject;
            $groupConfig->type = $groupDef->getGroupType();

            $groupConfig->details = new BaseObject;
            $groupConfig->details->machines = $groupDef->getHostsAsConfig();

            if ($groupDef->hasProvisioningAdapters()) {
                $groupConfig->provisioning = $groupDef->getProvisioningAsConfig();
            }
            $groupConfig->baseFolder = $groupDef->getBaseFolder();
            $retval->groups[] = $groupConfig;
        }

        // do we have any module settings to carry over?
        if ($this->hasModuleSettings()) {
            $retval->moduleSettings = new BaseObject;
            $retval->moduleSettings->mergeFrom($this->getModuleSettings());
        }

        // all done
        return $retval;
    }
}
