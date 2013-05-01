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
 * @package   Stone/ObjectLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */

namespace DataSift\Stone\ObjectLib;

use ReflectionObject;
use ReflectionProperty;
use stdClass;

/**
 * A base class to be a home for generic helper methods, at least until
 * we move to PHP 5.5 and traits
 *
 * We're skipping PHP 5.4 because of production problems with APC
 *
 * Our emphasis here is to make it easier to work with classes w/out having
 * to create getters/setters for everything.  Not that there's anything
 * wrong with getters/setters, it's just that we can get away with writing
 * a lot less code in our particular JSON-driven environment :)
 *
 * @category  Libraries
 * @package   Stone/ObjectLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class BaseObject extends stdClass
{
    // ====================================================================
    //
    // merging two objects together
    //
    // --------------------------------------------------------------------

    /**
     * Copy all of the properties from another object (loaded from a
     * .json file or received over the net) into our own properties
     *
     * We convert any objects nested inside the other object into
     * BaseObjects too, so that all the helpers defined in BaseObject
     * are available.
     *
     * @param  mixed $src the object to copy
     * @return void
     */
    public function mergeFrom($src)
    {
        // special case - do we have a basic value?
        if (!is_object($src) && !is_array($src))
        {
            // as we don't know what else to call it, we'll just add it
            // to 'value'
            $this->value = $src;

            // nothing more to do here
            return;
        }

        // if we get here, then we have a complex structure to evaluate,
        // which we're going to handle recursively
        $this->mergeInto($this, $src);
    }

    /**
     * merge a (possibly complex) object or array into our (possibly complex)
     * object or array
     *
     * along the way, we'll convert any plain old stdClass objects into
     * funky new BaseObjects
     *
     * this was originally created for supporting merging two or more
     * config files (e.g. a default one, and an overrides file) into
     * one usable data structure
     *
     * PLEASE NOTE that this method is recursive, which will cause
     * problems if you go nuts on how deep your data structures are
     *
     * @param  object|array $ours
     *         the array or object that we're adding to
     * @param  object|array $theirs
     *         the array or object that we're merging from
     * @return object|array
     *         $ours, after we've merged across from $theirs
     */
    private function mergeInto($ours, $theirs)
    {
        // let's see what is inside their object
        foreach ($theirs as $key => $value)
        {
            // special case - conversion only
            if (!isset($ours->$key))
            {
                // what are we looking at inside their object?
                if (is_object($value) && ($value instanceof stdClass))
                {
                    // for convenience, turn stdClass into more BaseObjects
                    $ours->$key = $this->convertObject($value);
                }
                else if (is_array($value))
                {
                    // for convenience, turn any stdClass objects that are
                    // inside this array into more BaseObjects
                    $ours->$key = $this->convertArray($value);
                }
                else
                {
                    $ours->$key = $value;
                }

                // all done converting
                continue;
            }

            // we have a clash ...
            //
            // if ours and theirs are incompatible data types, we will
            // have to just overwrite
            //
            // this is how we handle merging into an existing object
            if (is_object($ours->$key))
            {
                if (!is_object($value))
                {
                    // cannot merge object and non-object
                    $ours->$key = $value;
                    continue;
                }

                if (!$ours->$key instanceof stdClass || !$value instanceof stdClass)
                {
                    // we cannot merge any complex classes at all
                    $ours->$key = $value;
                    continue;
                }

                // if we get here, then we have two compatible objects to merge
                //
                // we don't know what's inside their object ... recursion to
                // the rescue :(
                $ours->$key = $this->mergeInto($ours->$key, $value);
                continue;
            }

            // this is how we handle merging into an existing array
            if (is_array($ours->$key))
            {
                if(!is_array($value))
                {
                    // cannot merge array and non-array
                    $ours->$key = $value;
                    continue;
                }

                // if we get here, then we have two arrays to merge
                $ours->$key = $this->mergeInfo($ours->$key, $value);
                continue;
            }

            // if we get here, then we have nothing that we can merge,
            // and can only overwrite
            $ours->$key = $value;
        }

        // all done
        return $ours;
    }

    /**
     * convert a stdClass into a BaseObject
     *
     * this does a deep conversion, and therefore can be recursive
     *
     * @param  stdClass $src
     *         the object that we want to convert
     * @return BaseObject
     *         the replacement object
     */
    private function convertObject(stdClass $src)
    {
        $return = new BaseObject();
        $return->mergeFrom($src);

        return $return;
    }

    /**
     * look inside an array, to see if there are any stdClass objects
     * that need converting into BaseObjects
     *
     * this does a deep conversion, and therefore can be recursive
     *
     * @param  array $src
     *         the array to convert
     * @return array
     *         the replacement array
     */
    private function convertArray($src)
    {
        $mustConvert = false;

        // do we actually need to do any conversion?
        // let's inspect the array to make a decision
        $keys = array_keys($src);
        foreach ($keys as $key)
        {
            // we only want to convert in two cases
            //
            // we find a stdClass object (must be converted)
            // we find a nested array (we must look deeper)
            if ((is_object($src[$key]) && $src[$key] instanceof stdClass) || is_array($src[$key]))
            {
                $mustConvert = true;
                break;
            }
        }

        // can we just send back what we have received?
        if (!$mustConvert)
        {
            // yes :)
            return $src;
        }

        // if we get here, then we need to convert what we have
        //
        // this is what we'll send back to the caller
        $return = array();

        // let's iterate over the array once more
        foreach ($src as $key => $value)
        {
            // do we have a convertable object?
            if (is_object($value) && $object instanceof stdClass)
            {
                // yes - convert it
                $return[$key] = $this->convertObject($value);
            }
            // do we have a nested array?
            else if (is_array($value))
            {
                // yes - let's look inside it, in case there are stdClass
                // objects lurking within
                $return[$key] = $this->convertArray($value);
            }
            else
            {
                // whatever we have, it is not convertable
                // just copy it across
                $return[$key] = $value;
            }
        }

        // all done - return the replacement array to the caller
        return $return;
    }

    // ====================================================================
    //
    // Helpers
    //
    // These are mostly syntactic sugar, but they help a tiny bit with
    // code robustness because they can deal correctly with unset properties,
    // something many developers forget to check for
    //
    // --------------------------------------------------------------------

    /**
     * Do we have a named property set to non-null?
     *
     * @param  string $propName
     * @return boolean
     */
    public function has($propName)
    {
        return (isset($this->$propName));
    }

    /**
     * Is the named property set to true?
     *
     * @param  string $propName
     * @return boolean
     */
    public function is($propName)
    {
        return (isset($this->$propName) && $this->$propName);
    }

    /**
     * is the named property set to false?
     *
     * @param  string $propName
     * @return boolean
     */
    public function isNot($propName)
    {
        return (!isset($this->$propName) || $this->$propName != true);
    }

    /**
     * Do we have a named property, and is it a non-empty array?
     *
     * @param  string $propName
     * @return boolean
     */
    public function hasList($propName)
    {
        return (isset($this->$propName)
            && ((is_array($this->$propName) && count($this->$propName) > 0) ||
               (is_object($this->$propName))));
    }

    /**
     * retrieve the named property as an associative array, even if it is
     * actually an object
     *
     * @param  string $propName
     * @return array
     */
    public function getList($propName)
    {
        // do we have the property at all?
        if (!isset($this->$propName))
        {
            // no ... send back an empty list
            return array();
        }

        // is the property already a list?
        if (is_array($this->$propName))
        {
            // yes ... no conversion needed
            return $this->$propName;
        }

        // is the property something we can convert?
        if (is_object($this->$propName))
        {
            // yes
            $return = array();
            foreach ($this->$propName as $key => $value)
            {
                $return[$key] = $value;
            }

            return $return;
        }

        // if we get here, the property isn't naturally a list
        return array();
    }

    /**
     * return the named property as a string, or return the default if
     * the property isn't a string
     *
     * @param  string $propName name of property to retrieve
     * @param  string $default  default value to return if property not set
     * @return string
     */
    public function getString($propName, $default = '')
    {
        // does this property exist at all?
        if (!isset($this->$propName))
        {
            // no, so return the default
            return $default;
        }

        // is this property something that can be auto-converted to a
        // string reliably?
        if (is_string($this->$propName) || is_int($this->$propName) || is_double($this->$propName))
        {
            // yes
            return (string)$this->$propName;
        }

        // starting to clutch at straws now

        // a boolean, perhaps?
        if (is_bool(($this->$propName)))
        {
            if ($this->$propName)
            {
                return 'TRUE';
            }

            return 'FALSE';
        }

        // is it an object that can convert itself to a string?
        if (is_object($this->$propName))
        {
            $refObj = new ReflectionObject($this->$propName);
            if ($refObj->hasMethod('__toString'))
            {
                return (string)$this->$propName;
            }

            // sadly, the object cannot convert itself to a string
            return $default;
        }

        // add any other conversions in here

        // okay, we give up
        return $default;
    }

    /**
     * convert our public properties to an array
     *
     * @param string prefix
     *        an optional prefix to stick on the front of the property
     *        to form our array keys
     * @return array
     */
    public function getProperties_asList($prefix = null)
    {
        $return = array();

        // get a list of the properties of the $params object
        $refObj   = new ReflectionObject($this);
        $refProps = $refObj->getProperties(ReflectionProperty::IS_PUBLIC);

        // convert each property into an array entry
        foreach ($refProps as $refProp)
        {
            $propKey      = $refProp->getName();
            $retKey       = $propKey;

            // do we need to enforce the prefix?
            if ($prefix !== null && substr($this->$propKey, 0, strlen($prefix)) !== $prefix)
            {
                // yes we do
                $retKey = $prefix . $propKey;
            }

            // set the value
            $return[$retKey] = $this->$propKey;
        }

        // return the array that we've built
        return $return;
    }

    /**
     * magic method, called when there's an attempt to get a property
     * that doesn't actually exist
     *
     * @param  string $property name of the property being read
     * @throws E5xx_NoSuchProperty
     */
    public function __get($property)
    {
        throw new E5xx_NoSuchProperty(get_class($this), $property);
    }
}
