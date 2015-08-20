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
 * @package   Storyplayer/Prose
 * @author    Thomas Shipley <thomas.shipley@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Prose;

/**
 * A collection of functions for manipulating strings
 *
 * Great for testing APIs
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Thomas Shipley <thomas.shipley@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class FromString extends Prose{

    /**
     * Reduces a dot separated path e.g. fb.parent.context by one from
     * the end of the string.
     *
     * The input fb.parent.context -> fb.parent
     * @param $pathToReduce - The dot separated path to reduce by one
     * @return null|string - Returns the last element of the dot separated
     * path when > 1 elements. Otherwise the only element or null.
     */
    public function reduceDotSeparatedPathByOne($pathToReduce)
    {
        $parts = $this->splitDotSeparatedPath($pathToReduce);
        if (count($parts) == 0) {
            return null;
        } else if (count($parts) == 1) {
            return $parts[0];
        } else {
            array_pop($parts);
            return implode('.', $parts);
        }
    }

    /**
     * A wrapper around the explode function. Given a dot
     * separated path e.g. fb.parent.context return a array of the elements
     * @param $pathToSplit - The dot separated path to split
     * @return array - The elements of the path as a array
     */
    public function splitDotSeparatedPath($pathToSplit)
    {
        return explode('.', $pathToSplit);
    }
}