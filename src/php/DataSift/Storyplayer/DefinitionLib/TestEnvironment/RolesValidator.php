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

use Storyplayer\TestEnvironments\HostManager;
use Storyplayer\TestEnvironments\HostManagerValidator;
use Storyplayer\TestEnvironments\OsAdapter;
use Storyplayer\TestEnvironments\OsAdapterValidator;

/**
 * Logic for verifying a list of roles for a host
 *
 * @category  Libraries
 * @package   Storyplayer/DefinitionLib
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class TestEnvironment_RolesValidator
{
    /**
     * the host we are checking out roles for
     * @var TestEnvironment_HostDefinition
     */
    protected $host;

    /**
     * constructor
     *
     * @param TestEnvironment_HostDefinition $host
     *        the host that we are validating roles for
     */
    public function __construct($host)
    {
        $this->host = $host;
    }

    /**
     * validate a set of roles
     *
     * throws an exception if there's anything wrong with the roles
     *
     * @param  mixed $roles
     *         the roles to validate
     * @return void
     */
    public function validate($roles)
    {
        $this->validateMustBeArray($roles);

        // empty arrays are allowed
        if (count($roles) === 0) {
            return;
        }

        $this->validateMustBeKeyValuePairs($roles);
    }

    /**
     * make sure that the roles are an array
     *
     * @param  mixed $roles
     *         the roles to check
     * @return void
     */
    protected function validateMustBeArray($roles)
    {
        if (!is_array($roles)) {
            throw new E4xx_IllegalRolesList(
                $this->host->getTestEnvironmentName(),
                $this->host->getGroupId(),
                $this->host->getHostId(),
                $roles
            );
        }
    }

    /**
     * make sure that the roles are simple key / value pairs
     *
     * @param  array $roles
     *         the roles to check
     * @return void
     */
    protected function validateMustBeKeyValuePairs($roles)
    {
        foreach ($roles as $key => $value) {
            if (!is_string($value)) {
                throw new E4xx_IllegalRole(
                    $this->host->getTestEnvironmentName(),
                    $this->host->getGroupId(),
                    $this->host->getHostId(),
                    $value
                );
            }
        }
    }
}
