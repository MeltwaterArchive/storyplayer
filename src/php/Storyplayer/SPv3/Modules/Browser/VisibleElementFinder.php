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
 * @package   Storyplayer/Modules/Browser
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv3\Modules\Browser;

use Storyplayer\SPv3\Modules\Exceptions;

/**
 * Trait for assisting with finding a visible element from a larger list
 * @category  Libraries
 * @package   Storyplayer/Modules/Browser
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
trait VisibleElementFinder
{
    /**
     * @return \DataSift\WebDriver\WebDriverElement
     */
    public function returnNthVisibleElement($nth, $elements)
    {
        // what are we doing?
        $count = count($elements);
        $log = usingLog()->startAction("looking for element '{$nth}' out of array of {$count} element(s)");

        // special case - not enough elements, even if they were all
        // visible
        if ($nth >= count($elements)) {
            $log->endAction("not enough elements :(");
            throw Exceptions::newActionFailedException(__METHOD__, "no matching element found");
        }

        // let's track which visible element we're looking at
        $checkedIndex = 0;

        // if the page contains multiple matches, return the first one
        // that the user can see
        foreach ($elements as $element) {
            if (!$element->displayed()) {
                // DO NOT increment $checkedIndex here
                //
                // we only increment it for elements that are visible
                continue;
            }

            // skip hidden input fields
            // if ($element->name() == 'input') {
            //  try {
            //      $typeAttr = $element->attribute('type');
            //      if ($typeAttr == 'hidden') {
            //          // skip this
            //          continue;
            //      }
            //  }
            //  catch (Exception $e) {
            //      // no 'type' attribute
            //      //
            //      // not fatal
            //  }
            // }

            if ($checkedIndex == $nth) {
                // a match!
                $log->endAction();
                return $element;
            }
        }

        $msg = "no matching element found";
        $log->endAction($msg);
        throw Exceptions::newActionFailedException(__METHOD__, $msg);
    }
}
