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
 * @package   Storyplayer/SystemsUnderTestLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\SystemsUnderTestLib;

use DataSift\Storyplayer\ConfigLib\WrappedConfig;

/**
 * the class for the config that represents a single system under test
 *
 * @category  Libraries
 * @package   Storyplayer/SystemsUnderTestLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class SystemUnderTestConfig extends WrappedConfig
{
    public function validateConfig()
    {
        // the config we are checking
        $config = $this->getConfig();

        // make sure it is an object, and not an array
        if (!is_object($config)) {
            throw new E4xx_SystemUnderTestConfigMustBeAnObject($this->getFilename());
        }

        $this->validateAppSettings();
        $this->validateRoles();
    }

    protected function validateAppSettings()
    {
        // do we have any app settings to validate?
        if (!$this->hasData('appSettings')) {
            // nothing to see here, move along
            return;
        }

        $appSettings = $this->getData('appSettings');
        if (!is_object($appSettings)) {
            throw new E4xx_SystemUnderTestAppSettingsMustBeAnObject($this->getFilename());
        }

        // we don't care what (if anything) is inside the appSettings
        // section
        //
        // all done
    }

    protected function validateRoles()
    {
        // do we have any roles to validate?
        if (!$this->hasData('roles')) {
            // nothing to see here, move along
            return;
        }

        // roles is meant to be an array of objects
        $roles = $this->getData('roles');
        if (!is_array($roles)) {
            throw new E4xx_SystemUnderTestRolesMustBeAnArray($this->getFilename());
        }

        // make sure each role in the list fits what we want
        foreach ($roles as $index => $roleObj) {
            if (!is_object($roleObj)) {
                throw new E4xx_SystemUnderTestRoleMustBeAnObject($this->getFilename(), $index);
            }
            if (!isset($roleObj->role)) {
                throw new E4xx_SystemUnderTestRoleMustSayWhichRoleItIs($this->getFilename(), $index);
            }
            if (!is_string($roleObj->role)) {
                throw new E4xx_SystemUnderTestRoleNameMustBeString($this->getFilename(), $index);
            }
            if (!isset($roleObj->params)) {
                throw new E4xx_SystemUnderTestRoleMustHaveParams($this->getFilename(), $index);
            }
            if (!is_object($roleObj->params)) {
                throw new E4xx_SystemUnderTestRoleParamsMustBeAnObject($this->getFilename(), $index);
            }
        }
    }
}
