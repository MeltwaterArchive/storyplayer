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
 * @package   Storyplayer/Modules/Exceptions
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv3\Modules;

use Storyplayer\SPv3\Modules\Exceptions\ActionFailedException;
use Storyplayer\SPv3\Modules\Exceptions\ExpectFailedException;
use Storyplayer\SPv3\Modules\Exceptions\NotImplementedException;
use Storyplayer\SPv3\Modules\Exceptions\ObsoleteModuleException;
use Storyplayer\SPv3\Modules\Exceptions\StoryCannotRunException;
use Storyplayer\SPv3\Modules\Exceptions\StoryShouldFailException;

class Exceptions
{
    /**
     * create a new ActionFailedException
     *
     * @param  string $methodName
     *         should always be the __METHOD__ constant
     * @param  string $reason
     *         why did the action fail?
     * @param  array $params
     *         supporting data on why the action failed
     * @return ActionFailedException
     */
    public static function newActionFailedException($methodName, $reason = '', $params = [])
    {
        return new ActionFailedException($methodName);
    }

    /**
     * create a new ExpectFailedException
     *
     * @param  string $methodName
     *         should always be the __METHOD__ constant
     * @param  string $expected
     *         an explanation of the result that was expected
     * @param  string $actual
     *         an explanation of what the result was
     * @return ExpectFailedException
     */
    public static function newExpectFailedException($methodName, $expected, $actual)
    {
        return new ExpectFailedException($methodName, $expected, $actual);
    }

    /**
     * create a new NotImplementedException
     *
     * @param  string $methodName
     *         should be the equivalent of the __METHOD__ constant
     * @return NotImplementedException
     */
    public static function newNotImplementedException($methodName)
    {
        return new NotImplementedException($methodName);
    }

    /**
     * create a new ObsoleteModuleException
     *
     * @param  string $moduleName
     *         the name of the module that is now obsolete
     * @param  string $replacementName
     *         the name of the module that should be used going forward
     * @return ObsoleteModuleException
     */
    public static function newObsoleteModuleException($moduleName, $replacementName)
    {
        return new ObsoleteModuleException($moduleName, $replacementName);
    }

    /**
     * create a new StoryCannotRunException
     *
     * @param  string $reason
     *         why can't the story run?
     * @return StoryCannotRunException
     */
    public static function newStoryCannotRunException($reason)
    {
        return new StoryCannotRunException($reason);
    }

    /**
     * create a new StoryShouldFailException
     *
     * @return StoryShouldFailException
     */
    public static function newStoryShouldFailException()
    {
        return new StoryShouldFailException();
    }
}
