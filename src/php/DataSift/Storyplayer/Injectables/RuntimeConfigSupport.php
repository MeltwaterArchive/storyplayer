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
 * @package   Storyplayer/Injectables
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Injectables;

use DataSift\Storyplayer\Injectables;
use DataSift\Storyplayer\Cli\RuntimeConfigManager;

/**
 * support for working with the persistent, app-generated config
 *
 * @category  Libraries
 * @package   Storyplayer/Injectables
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
trait RuntimeConfigSupport
{
    protected $runtimeConfigManager = null;
    protected $runtimeConfig = null;

    /**
     * @return RuntimeConfigManager
     */
    public function initRuntimeConfigSupport(Injectables $injectables)
    {
        // create the runtime config's manager
        $this->runtimeConfigManager = new RuntimeConfigManager();

        // create the folder where we will store the persistent config
        $this->runtimeConfigManager->makeConfigDir($injectables->output);

        // load any config from the last time Storyplayer ran
        $this->runtimeConfig = $this->runtimeConfigManager->loadRuntimeConfig($injectables->output);

        // all done
        return $this->runtimeConfigManager;
    }

    /**
     * @return \DataSift\Storyplayer\Cli\RuntimeConfigManager
     */
    public function getRuntimeConfigManager()
    {
        if ($this->runtimeConfigManager === null) {
            throw new E4xx_NotInitialised('runtimeConfigManager');
        }

        return $this->runtimeConfigManager;
    }

    /**
     * @return object
     */
    public function getRuntimeConfig()
    {
        if ($this->runtimeConfig === null) {
            throw new E4xx_NotInitialised('runtimeConfig');
        }

        return $this->runtimeConfig;
    }
}
