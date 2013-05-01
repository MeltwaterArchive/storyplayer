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
 * @package   Stone/DataLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */

namespace DataSift\Stone\DataLib;

use ReflectionObject;
use ReflectionProperty;

/**
 * A helper class for working with potentially unprintable data
 *
 * @category  Libraries
 * @package   Stone/DataLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class DataPrinter
{
    /**
     * used when we want arrays / objects to be converted to JSON
     * format
     */
    const JSON_FORMAT = "convertToJson";

    /**
     * used when we want arrays / objects to be converted using
     * PHP's print_r() function
     */
    const PRINTR_FORMAT = "convertToPrintr";

    /**
     * used when we want arrays / objects to be converted using
     */
    const VAREXPORT_FORMAT = "convertToVarexport";

    /**
     * the name of the method to call to convert arrays / objects
     * to strings
     *
     * @var string
     */
    protected $convertor;

    /**
     * constructor. indicate how we want arrays / objects converted
     * to strings
     *
     * @param string $format
     *        use one of the *_FORMAT constants
     */
    public function __construct($format = self::JSON_FORMAT)
    {
        $this->convertor = $format;
    }

    /**
     * convert a PHP variable to a printable string
     *
     * @param  mixed $mixed
     *         the variable to be converted
     *
     * @return string
     *         a printable string
     */
    public function convertToString($mixed)
    {
        // what are we looking at?
        if (is_object($mixed)) {
            return $this->objectToString($mixed);
        }
        if (is_array($mixed)) {
            $convertor = $this->convertor;
            return $this->$convertor($mixed);
        }
        if (is_resource($mixed)) {
            return $this->resourceToString($mixed);
        }

        // if we get here, then no complicated conversion required
        return (string)$mixed;
    }

    /**
     * convert an object to a printable string
     *
     * if the object defines a string convertor method (the __toString()
     * method), then we will use that.
     *
     * if it doesn't, then we'll convert it to the format requested
     * in our constructor
     *
     * @param  object $obj
     *         the object to convert
     *
     * @return string
     *         a printable string
     */
    public function objectToString($obj)
    {
        // does the object have a '__toString' method?
        if (method_exists($obj, '__toString')) {
            // yes - let PHP convert it for us
            return (string)$obj;
        }

        // no, object does not have a string convertor predefined
        $convertor = $this->convertor;
        return $this->$convertor($obj);
    }

    /**
     * convert a PHP resource to a printable string
     *
     * @param  resource $resource
     *         the resource to convert
     *
     * @return string
     *         a printable string
     */
    public function resourceToString($resource)
    {
        // nothing else we can do but print a static string
        //
        // it would be great if we could see inside these just a little
        // bit one day
        return "(PHP resource)";
    }

    /**
     * convert an array or object to JSON-encoding
     *
     * @param  array|object $input
     *         the variable to convert
     *
     * @return string
     *         a JSON-encoded string
     */
    public function convertToJson($input)
    {
        return json_encode($input);
    }

    /**
     * convert an array or object to print_r() format
     *
     * @param  array|object $input
     *         the variable to convert
     *
     * @return string
     *         a string created by print_r()
     */
    public function convertToPrintr($input)
    {
        return print_r($input, true);
    }

    /**
     * convert an array or object to var_export() format
     *
     * @param  array|object $input
     *         the variable to convert
     *
     * @return string
     *         a string created by var_export()
     */
    public function convertToVarexport($input)
    {
        return var_export($input, true);
    }
}
