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
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\PlayerLib;

use Prose\Prose;

/**
 * Helper class to load Prose classes and create objects from them
 *
 * @category  Libraries
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Prose_Loader
{
    private $namespaces = array(
        "Prose",
        "DataSift\\Storyplayer\\Prose"
    );

    public function setNamespaces($namespaces = array())
    {
        // a list of the namespaces we're going to search for this class
        //
        // we always search the generic 'Prose' namespace first, in case
        // users don't want to uniquely namespace their Prose classes
        $this->namespaces = array ("Prose");

        // append the namespaces we've been asked to search
        foreach ($namespaces as $namespace) {
            $this->namespaces[] = $namespace;
        }

        // we search our own namespace last, as it allows the user to
        // replace our Prose with their own if they prefer
        $this->namespaces[] = "DataSift\\Storyplayer\\Prose";
    }

    public function determineProseClassFor($methodName)
    {
        // this new, simplified naming scheme was introduced for
        // Storyplayer 1.4
        //
        // it isn't considered a b/c break, because we didn't document
        // how to create modules until v1.4 was released
        $className = ucfirst($methodName);

        // all done
        return $className;
    }

    /**
     * @param string $className
     */
    public function loadProse(StoryTeller $st, $className, $constructorArgs)
    {
        // can we find the class?
        foreach ($this->namespaces as $namespace) {
            // what is the full name of the class (inc namespace) to
            // search for?
            $namespacedClassName = $namespace . "\\" . $className;

            // is there such a class?
            if (class_exists($namespacedClassName)) {
                // yes there is!!
                //
                // create an instance of the class
                $return = new $namespacedClassName(
                    $st,
                    $constructorArgs);

                // make sure our new object is an instance of 'Prose'
                if (!$return instanceof Prose) {
                    throw new E5xx_NotAProseClass($namespacedClassName);
                }

                // return our newly-minted object
                return $return;
            }
        }

        // if we get there, then we cannot find a suitable class in
        // any of the namespaces that we know about
        return null;
    }

    /**
     * this needs moving into its own trait perhaps, or into a static
     * inside the Stone library
     *
     * @param  [type] $methodName [description]
     * @return [type]             [description]
     */
    protected function convertMethodNameToWords($methodName)
    {
        // turn the method name into an array of words
        $words = explode(' ', strtolower(preg_replace('/([^A-Z])([A-Z])/', "$1 $2", $methodName)));

        // all done
        return $words;
    }
}