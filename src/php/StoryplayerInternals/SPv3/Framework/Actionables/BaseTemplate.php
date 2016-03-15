<?php

/**
 * Copyright (c) 2011-present Mediasift Ltd
 * Copyright (c) 2016-present Ganbaro Digital Ltd
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
 * @package   StoryplayerInternals/Framework
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2016-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace StoryplayerInternals\SPv3\Framework\Actionables\BaseTemplate;

use ReflectionObject;

use GanbaroDigital\Actionables\Values\Actionable;

/**
 * Base class for all templates
 *
 * @category  Libraries
 * @package   StoryplayerInternals/Framework
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2016-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class BaseTemplate
{
    /**
     * I don't know what I'm doing with parameters in templates yet
     * @return [type] [description]
     */
    public function getParams()
    {
        return $this->params;
    }

    public function setParams($params = array())
    {
        $this->params = $params;
    }

    /**
     * does our subclass provide a method called 'perPhaseSetup'?
     *
     * @return boolean
     *         TRUE if it does
     *         FALSE otherwise
     */
    public function hasPerPhaseSetup()
    {
        return method_exists($this, 'perPhaseSetup');
    }

    /**
     * does our subclass provide a method called 'perPhaseTeardown'?
     *
     * @return boolean
     *         TRUE if it does
     *         FALSE otherwise
     */
    public function hasPerPhaseTeardown()
    {
        return method_exists($this, 'perPhaseTeardown');
    }

    /**
     * does our subclass provide a method called 'deviceSetup'?
     *
     * @return boolean
     *         TRUE if it does
     *         FALSE otherwise
     */
    public function hasDeviceSetup()
    {
        return method_exists($this, 'deviceSetup');
    }

    /**
     * does our subclass provide a method called 'deviceTeardown'?
     *
     * @return boolean
     *         TRUE if it does
     *         FALSE otherwise
     */
    public function hasDeviceTeardown()
    {
        return method_exists($this, 'deviceTeardown');
    }

    /**
     * return our 'perPhaseSetup' method as a callable
     *
     * @return callable
     */
    public function getPerPhaseSetup()
    {
        return new Actionable(
            [$this, 'perPhaseSetup'],
            $this->getSourceFilename(),
            [ 'perPhaseSetup' ]
        );
    }

    /**
     * return our 'perPhaseTeardown' method as a callable
     *
     * @return callable
     */
    public function getPerPhaseTeardown()
    {
        return new Actionable(
            [$this, 'perPhaseTeardown'],
            $this->getSourceFilename(),
            [ 'perPhaseTeardown' ]
        );
    }

    /**
     * return our 'deviceSetup' method as a callable
     *
     * @return callable
     */
    public function getDeviceSetup()
    {
        return new Actionable(
            [$this, 'deviceSetup'],
            $this->getSourceFilename(),
            [ 'deviceSetup' ]
        );
    }

    /**
     * return our 'deviceTeardown' method as a callable
     *
     * @return callable
     */
    public function getDeviceTeardown()
    {
        return new Actionable(
            [$this, 'deviceTeardown'],
            $this->getSourceFilename(),
            [ 'deviceTeardown' ]
        );
    }

    /**
     * which file is this template defined in?
     *
     * @return string
     */
    public function getSourceFilename()
    {
        $refObj = new ReflectionObject($this);
        return $refObj->getFileName();
    }
}
