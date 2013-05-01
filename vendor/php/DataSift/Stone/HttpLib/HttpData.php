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
 * @package   Stone/HttpLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */

namespace DataSift\Stone\HttpLib;

/**
 * A simple mechanism for safely processing incoming data
 *
 * @category  Libraries
 * @package   Stone/HttpLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class HttpData
{
    /**
     * the original data, before having been validated or filtered
     * @var array
     */
    protected $unchecked = array();

    /**
     * the filtered data cache - provided it has passed validation!
     * @var array
     */
    protected $checked = array();

    /**
     * A list of the errors reported during validation (if any)
     * @var array
     */
    protected $validationErrors = array();

    /**
     * A list of fields that we expected, but which were not supplied
     * @var array
     */
    protected $missingData = array();

    /**
     * constructor.  Pass in the data you wish to wrap.
     *
     * $_GET or $_POST are the two that are normally passed in
     *
     * @param array $dataset the data to wrap
     */
    public function __construct($dataset)
    {
        $this->unchecked = $dataset;
    }

    /**
     * add additional data (for example, parsed out of a route) to the
     * list of unchecked variables
     *
     * @param string $key
     *        the name of the variable to set
     * @param string $value
     *        the value to set the variable to
     */
    public function addData($key, $value)
    {
        $this->unchecked[$key] = $value;
    }

    /**
     * Does the wrapped data contain the given key?
     *
     * @param  string  $key the key to test
     * @return boolean
     */
    public function hasData($key)
    {
        return isset($this->unchecked[$key]);
    }

    /**
     * The preferred way to check a piece of data
     *
     * @param  GenericDataItem $data object representing the data to allow
     * @param  string          $name an override name (optional)
     * @return void
     */
    public function allowDataItem(GenericDataItem $data, $name = null)
    {
        // override the dataitem's name if requested
        if ($name !== null)
        {
            $data->name = $name;
        }

        // ask the data item to sanitise our data
        $data->sanitizeHttpData($this);
    }

    /**
     * Sanitise a piece of data
     *
     * This should only be called from GenericDataItem or a subclass of that
     *
     * @param  string   $key        the name of the data to sanitize
     * @param  callback $validator  function($data, &$errors) callback
     * @param  callback $filter     function($data) callback
     * @param  boolean  $isRequired is this field required?
     * @param  mixed    $default    what is the default if data is missing?
     * @return boolean  true on success, false otherwise
     */
    public function sanitizeKey($key, $validator, $filter, $isRequired = false, $default = null)
    {
        // make sure we're not repeating ourselves
        if (isset($this->checked[$key]))
        {
            return true;
        }

        // do we have the data?
        $data = $default;
        if (isset($this->unchecked[$key]))
        {
            $data = $this->unchecked[$key];
        }

        // should we have the data?
        if ($data === null && $isRequired)
        {
            // someone forgot to provide the data
            $this->missingData[$key] = $key;
            return false;
        }

        // we're going to apply validation & filtering to the data
        // that we have

        // does the data pass validation?
        $errors = array();
        if (!$validator($data, $errors) || count($errors) > 0)
        {
            // no, it does not
            //
            // stash the validation errors for future retrieval
            $this->validationErrors[$key] = $errors;

            // do NOT put an entry in the $checked array!

            // bail - no point filtering the data
            return false;
        }

        // the data has passed validation

        // get the filtered version of the data
        $this->checked[$key] = $filter($data);

        // all done
        return true;
    }

    /**
     * Helper method for complex data types which are made up of multiple
     * pieces of data (e.g. PublishRate).
     *
     * @param string $key   the array key to set
     * @param mixed  $value the array value to set
     */
    public function setFilteredData($key, $value)
    {
        $this->checked[$key] = $value;
    }

    /**
     * get the data
     *
     * @param  string $key the key of the data to retrieve
     * @return mixed
     */
    public function getFilteredData($key)
    {
        // has this data been checked?
        if (!isset($this->checked[$key]))
        {
            // no - so you can't have it!
            return null;
        }

        // yes - let the caller have it
        return $this->checked[$key];
    }

    /**
     * get all of the data that has been successfully validated and
     * sanitized
     *
     * @return array
     */
    public function getAllFilteredData()
    {
        return $this->checked;
    }

    /**
     * get the data, encoding it for output via HTML
     *
     * @param  string $key     the key of the data to retrieve
     * @param  string $default default value if data does not exist
     * @return string
     */
    public function getHtmlData($key, $default = null)
    {
        // do we need to send back the default value?
        if (!isset($this->checked[$key]))
        {
            return htmlentities($default);
        }

        // return the encoded data
        return htmlentities($this->checked[$key]);
    }

    /**
     * get the data, encoding it for inclusion in a URL
     *
     * @param  string $key     the key of the data to retrieve
     * @return string
     */
    public function getUrlData($key)
    {
        // do we need to send back the default value?
        if (!isset($this->checked[$key]))
        {
            return '';
        }

        // return the encoded data
        return urlencode($this->checked[$key]);
    }

    /**
     * get a list of fields that we expected, but which weren't present
     * amongst the data we have validated to date
     *
     * @return array
     */
    public function getMissingDataAsList()
    {
        return $this->missingData;
    }
}