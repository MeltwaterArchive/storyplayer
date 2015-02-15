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
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Cli;

use Phix_Project\ValidationLib4\ValidationResult;
use Phix_Project\ValidationLib4\File_MustBeValidFile;

class Feature_TestUsersValidator extends File_MustBeValidFile
{
    const MSG_EMPTYFILE = "File '%value%' appears to be empty";
    const MSG_INVALIDJSON = "File '%value%' contains invalid JSON";
    const MSG_NOUSERS = "File '%value%' does not contain any users";

    /**
     *
     * @param  mixed $value
     * @param  ValidationResult|null $result
     * @return ValidationResult
     */
    public function validate($value, ValidationResult $result = null)
    {
        // make sure we have a file first
        $result = parent::validate($value, $result);
        if ($result->hasErrors()) {
            return $result;
        }

        // is the file valid JSON?
        $contents = file_get_contents($value);
        if (empty($contents)) {
            $result->addError(static::MSG_EMPTYFILE);
            return $result;
        }
        $data = @json_decode($contents);
        if ($data === null) {
            $result->addError(static::MSG_INVALIDJSON);
            return $result;
        }

        // does the file contain any users?
        if (!is_object($data)) {
            $result->addError(static::MSG_NOUSERS);
            return $result;
        }

        // all done
        return $result;
    }
}
