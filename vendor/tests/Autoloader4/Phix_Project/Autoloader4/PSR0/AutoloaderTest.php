<?php

/**
 * Copyright (c) 2012-present Stuart Herbert.
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
 * @copyright   2011 Stuart Herbert www.stuartherbert.com
 * @copyright   2010 Gradwell dot com Ltd. www.gradwell.com
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://www.phix-project.org/
 * @version     4.3.0
 */

namespace Phix_Project\Autoloader4;

use PHPUnit_Framework_TestCase;

class PSR0_AutoloaderTest extends PHPUnit_Framework_TestCase
{
    public function testCanAutoload()
    {
        // ----------------------------------------------------------------
        // perform the change

        $obj = new Dummy1();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof Dummy1);
    }

    public function testCanAutoloadTrait()
    {
        // ----------------------------------------------------------------
        // setup your test

        if (!function_exists("trait_exists")) {
                // no support for traits
                echo "no trait support";
                return;
        }

        $expectedTraits = array (
                'Phix_Project\Autoloader4\Trait1' => 'Phix_Project\Autoloader4\Trait1',
        );

        // ----------------------------------------------------------------
        // perform the change

        $obj = new Dummy2();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof Dummy2);
        $actualTraits = class_uses('Phix_Project\Autoloader4\Dummy2');
        $this->assertEquals($expectedTraits, $actualTraits);
    }

}
