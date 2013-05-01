<?php

/**
 * Copyright (c) 2011-present Stuart Herbert.
 * Copyright (c) 2010 Gradwell dot com Ltd.
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
 * @package     Phix_Project
 * @subpackage  Autoloader4
 * @author      Stuart Herbert <stuart@stuartherbert.com>
 * @copyright   2011-present Stuart Herbert www.stuartherbert.com
 * @copyright   2010 Gradwell dot com Ltd. www.gradwell.com
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://www.phix-project.org/
 * @version     4.3.0
 */

namespace Phix_Project\Autoloader4;

class Autoloader_Path
{
    static public function emptySearchList()
    {
        set_include_path("");
    }

    static public function searchFirst($dirList)
    {
        // make sure we have an array to iterate through
        if (!is_array($dirList))
        {
            $dirList = array($dirList);
        }

        // this is what we'll be adding to the search path when we're done
        $searchPathList = array();

        // iterate through the list of folders
        foreach ($dirList as $dir)
        {
            // get the absolute path
            $dir = realpath($dir);

            // remove the folder if it is already in the search list
            static::dontSearchIn($dir);

            // add it to the end of the new list
            $searchPathList[] = $dir;
        }

        // add the new list to the front of the path
        set_include_path(implode(PATH_SEPARATOR, $searchPathList) . PATH_SEPARATOR . get_include_path());
    }

    static public function searchLast($dirList)
    {
        // make sure we have an array to iterate through
        if (!is_array($dirList))
        {
            $dirList = array($dirList);
        }

        // this is what we'll be adding to the search path when we're done
        $searchPathList = array();

        // iterate through the list of folders
        foreach ($dirList as $dir)
        {
            // get the absolute path
            $dir = realpath($dir);

            // remove the folder if it is already in the search list
            static::dontSearchIn($dir);

            // add it to the end of the new list
            $searchPathList[] = $dir;
        }

        // add the new list to the front of the path
        set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $dirList));
    }

    static public function dontSearchIn($dir)
    {
        // check to make sure that $dir is not already in the path
        $pathToSearch = explode(PATH_SEPARATOR, get_include_path());

        foreach ($pathToSearch as $dirToSearch)
        {
            $dirToSearch = realpath($dirToSearch);
            if ($dirToSearch == $dir)
            {
                // we have found it
                // remove it from the list
                // $key points to the *next* entry in the list,
                // not the current entry
                $key = key($pathToSearch);
                $key -= 1;
                unset($pathToSearch[$key]);
            }
        }

        // set the revised search path
        set_include_path(implode(PATH_SEPARATOR, $pathToSearch));
    }
}

