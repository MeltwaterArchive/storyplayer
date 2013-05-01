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
 * @package   Stone/ApiLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */

namespace DataSift\Stone\ApiLib;

use ReflectionObject;
use ReflectionProperty;

/**
 * Helpers for dealing with API data
 *
 * @category  Libraries
 * @package   Stone/ApiLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class ApiHelpers
{
    /**
     * convert a set of params (which may be an array, or which may be
     * a stdClass) into an array, for consumption elsewhere
     *
     * @param  mixed $params the parameters that need to be normalised
     * @return array
     */
    static public function normaliseParams($params)
    {
        // are the params already in array form?
        if (is_array($params))
        {
            // yes - nothing for us to do here
            return $params;
        }

        // if we get here, we need to conver the params into an array
        $paramsToSub = array();

        // get a list of the properties of the $params object
        $refObj   = new ReflectionObject($params);
        $refProps = $refObj->getProperties(ReflectionProperty::IS_PUBLIC);

        // convert each property into an array entry
        foreach ($refProps as $refProp)
        {
            $propName = $refProp->getName();
            $paramsToSub[$propName] = $params->$propName;
        }

        // return the array that we've built
        return $paramsToSub;
    }

    /**
     * Merge two sets of params into a single list
     *
     * @param  mixed $params1 first set of params to merge
     * @param  mixed $params2 second set of params to merge
     * @return array
     */
    static public function mergeParams($params1, $params2)
    {
        $paramsToMerge1 = self::normaliseParams($params1);
        $paramsToMerge2 = self::normaliseParams($params2);

        return array_merge($paramsToMerge1, $paramsToMerge2);
    }
}