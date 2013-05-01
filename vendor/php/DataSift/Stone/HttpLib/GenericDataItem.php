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

use ReflectionObject;
use ReflectionProperty;
use stdClass;

use DataSift\Stone\ObjectLib\BaseObject;

/**
 * Base class to represent data received over HTTP
 *
 * @category  Libraries
 * @package   Stone/HttpLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class GenericDataItem extends BaseObject
{
    /**
     * the name of the query string / post parameter
     * @var string
     */
    public $name = null;

    /**
     * is this data item allowed to be missing?
     * @var boolean
     */
    public $required = false;

    /**
     * what is the default value of this data item, if it is missing?
     * @var mixed
     */
    public $default = null;

    /**
     * is an empty value a valid value for this data item?
     * @var boolean
     */
    public $allowEmpty = true;

    // ==================================================================
    //
    // Helpers for working with data validation
    //
    // ------------------------------------------------------------------

    /**
     * get the filters to apply to HttpLib's HttpData::sanitizeKey()
     * @return array
     */
    protected function getDataFilters()
    {
        return array(
            $this->name => function($data) {
                return filter_var($data, FILTER_SANITIZE_STRING);
            }
        );
    }

    /**
     * get the validators to apply to HttpLib's HttpData::sanitizeKey()
     * @return array
     */
    protected function getDataValidators()
    {
        return array(
            $this->name => function($data, &$errors) {
                if (empty($data))
                {
                    $errors[] = "Cannot be empty";
                    return false;
                }

                return true;
            }
        );
    }

    /**
     * get a list of the default values for this widget
     * @return array
     */
    protected function getDefaults()
    {
        return array($this->name => $this->default);
    }

    /**
     * sanitize the data represented by this class
     *
     * @param  HttpData $data the data container to assist
     * @return void
     */
    public function sanitizeHttpData(HttpData $data)
    {
        // get our list of validators and filters
        $validators = $this->getDataValidators();
        $filters    = $this->getDataFilters();
        $defaults   = $this->getDefaults();

        // apply the validators, filters, and defaults
        $success = true;
        foreach ($validators as $name => $validator)
        {
            if (!$data->sanitizeKey($name, $validator, $filters[$name], $this->required, $defaults[$name]))
            {
                $success = false;
            }
        }

        // did the data pass the tests?
        if (!$success)
        {
            // no, it did not
            return;
        }

        // do we need to combine the pieces of data into a single
        // data item?
        if (count($defaults) > 1)
        {
            // yes, we do
            $data->setFilteredData($this->name, $this->getCombinedValue($data));
        }
    }
}