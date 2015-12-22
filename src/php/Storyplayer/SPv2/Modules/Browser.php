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
 * @package   Storyplayer
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv2\Modules;

use Storyplayer\SPv2\Modules\Browser\ExpectsBrowser;
use Storyplayer\SPv2\Modules\Browser\ExpectsForm;
use Storyplayer\SPv2\Modules\Browser\FromBrowser;
use Storyplayer\SPv2\Modules\Browser\FromForm;
use Storyplayer\SPv2\Modules\Browser\UsingBrowser;
use Storyplayer\SPv2\Modules\Browser\UsingForm;

use DataSift\Storyplayer\PlayerLib\StoryTeller;

class Browser
{
    /**
     * returns the ExpectsBrowser submodule
     *
     * @return Storyplayer\SPv2\Modules\Browser\ExpectsBrowser;
     */
    public static function expectsBrowser()
    {
        return new ExpectsBrowser(StoryTeller::instance());
    }

    /**
     * returns the ExpectsForm submodule
     *
     * @param int $formId
     *        the value of the form's 'id' attribute
     * @return Storyplayer\SPv2\Modules\Browser\ExpectsForm;
     */
    public static function expectsForm($formId)
    {
        return new ExpectsForm(StoryTeller::instance(), [$formId]);
    }

    /**
     * returns the FromBrowser submodule
     *
     * @return Storyplayer\SPv2\Modules\Browser\FromBrowser;
     */
    public static function fromBrowser()
    {
        return new FromBrowser(StoryTeller::instance());
    }

    /**
     * returns the FromForm submodule
     *
     * @param int $formId
     *        the value of the form's 'id' attribute
     * @return Storyplayer\SPv2\Modules\Browser\FromForm;
     */
    public static function fromForm($formId)
    {
        return new FromForm(StoryTeller::instance(), [$formId]);
    }

    /**
     * returns the UsingBrowser submodule
     *
     * @return Storyplayer\SPv2\Modules\Browser\UsingBrowser;
     */
    public static function usingBrowser()
    {
        return new UsingBrowser(StoryTeller::instance());
    }

    /**
     * returns the UsingForm submodule
     *
     * @param int $formId
     *        the value of the form's 'id' attribute
     * @return Storyplayer\SPv2\Modules\Browser\UsingForm;
     */
    public static function usingForm($formId)
    {
        return new UsingForm(StoryTeller::instance(), $formId);
    }
}
