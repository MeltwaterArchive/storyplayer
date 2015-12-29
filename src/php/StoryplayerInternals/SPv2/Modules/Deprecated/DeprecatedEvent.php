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
 * @package   StoryplayerInternals/Modules/Deprecated
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace StoryplayerInternals\SPv2\Modules\Deprecated;

use GanbaroDigital\Reflection\Requirements\RequireInteger;
use GanbaroDigital\Reflection\Requirements\RequireStringy;
use StoryplayerInternals\SPv2\Modules\Events\Event;

/**
 * @method string getWhat()
 * @method string getFile()
 * @method int getLine()
 * @method string getSince()
 * @method string getUrl()
 */
class DeprecatedEvent extends Event
{
    /**
     * @param string $what
     *        what has been deprecated?
     * @param string $file
     *        where was this called? use __FILE__ constant
     * @param string $line
     *        where was this called? use __LINE__ constant
     * @param string $since
     *        which version of Storyplayer was it deprecated in?
     * @param string $url
     *        where can the user go to learn more?
     */
    public function __construct($what, $file, $line, $since, $url)
    {
        // robustness
        RequireStringy::check($what);
        RequireStringy::check($file);
        RequireInteger::check($line);
        RequireStringy::check($since);
        RequireStringy::check($url);

        // remember the values
        $this->setWhat($what);
        $this->setFile($file);
        $this->setLine($line);
        $this->setSince($since);
        $this->setUrl($url);

        // no more changes
        $this->makeReadOnly();
    }

    /**
     * where was the deprecated feature called from?
     * @return string
     */
    public function getCalledFrom()
    {
        return $this->getFile() . '@' . $this->getLine();
    }
}
