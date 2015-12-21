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
 * @package   Storyplayer/Asserts
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv2\Modules\Asserts;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\PlayerLib\Story_Checkpoint;
use DataSift\Stone\ComparisonLib\ObjectComparitor;

/**
 * Assertions about the nature of, and contents of, objects
 *
 * @method void isObject()
 * @method void hasAttribute(mixed $name)
 * @method void hasAttributeWithValue(mixed $name, mixed $value)
 * @method void doesNotHaveAttribute(mixed $name)
 * @method void doesNotHaveAttributeWithValue(mixed $name, mixed $value)
 * @method void isSameAs(object $expected)
 * @method void isNotSameAs(object $expected)
 * @method void hasMethod(string $methodName)
 * @method void doesNotHaveMethod(string $methodName)
 * @method void isInstanceOf(string $className)
 * @method void isNotInstanceOf(string $className)
 * @method void equals(object $expected)
 * @method void doesNotEqual(object $expected)
 * @method void isEmpty()
 * @method void isNull()
 * @method void isNotNull()
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class AssertsObject extends AssertionsBase
{
    /**
     * @param \stdClass[] $params
     */
    public function __construct(StoryTeller $st, $params)
    {
        parent::__construct($st, new ObjectComparitor($params[0]));
    }
}