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
 * @package   Storyplayer/Browser
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv2\Modules\Browser;

use DataSift\Storyplayer\PlayerLib\StoryTeller;

/**
 * Helper class that allows us to write Prose where the action comes before
 * we say what DOM element we want to act upon
 *
 * @method mixed boxWithId(string $id)
 * @method mixed boxWithLabel(string $label)
 * @method mixed boxLabelled(string $label)
 * @method mixed boxWithName(string $name)
 * @method mixed boxNamed(string $name)
 * @method mixed boxWithClass(string $class)
 * @method mixed boxWithPlaceholder(string $text)
 * @method mixed boxWithTitle(string $title)
 * @method mixed boxWithLabelTextOrId(string $labelTextOrId)
 * @method mixed boxesWithId(string $id)
 * @method mixed boxesWithLabel(string $label)
 * @method mixed boxesLabelled(string $label)
 * @method mixed boxesWithName(string $name)
 * @method mixed boxesNamed(string $name)
 * @method mixed boxesWithClass(string $class)
 * @method mixed boxesWithPlaceholder(string $text)
 * @method mixed boxesWithTitle(string $title)
 * @method mixed boxesWithLabelTextOrId(string $labelTextOrId)
 * @method mixed buttonWithId(string $id)
 * @method mixed buttonWithLabel(string $label)
 * @method mixed buttonLabelled(string $label)
 * @method mixed buttonWithName(string $name)
 * @method mixed buttonNamed(string $name)
 * @method mixed buttonWithClass(string $class)
 * @method mixed buttonWithPlaceholder(string $text)
 * @method mixed buttonWithTitle(string $title)
 * @method mixed buttonWithLabelTextOrId(string $labelTextOrId)
 * @method mixed buttonsWithId(string $id)
 * @method mixed buttonsWithLabel(string $label)
 * @method mixed buttonsLabelled(string $label)
 * @method mixed buttonsWithName(string $name)
 * @method mixed buttonsNamed(string $name)
 * @method mixed buttonsWithClass(string $class)
 * @method mixed buttonsWithPlaceholder(string $text)
 * @method mixed buttonsWithTitle(string $title)
 * @method mixed buttonsWithLabelTextOrId(string $labelTextOrId)
 * @method mixed cellWithId(string $id)
 * @method mixed cellWithLabel(string $label)
 * @method mixed cellLabelled(string $label)
 * @method mixed cellWithName(string $name)
 * @method mixed cellNamed(string $name)
 * @method mixed cellWithClass(string $class)
 * @method mixed cellWithPlaceholder(string $text)
 * @method mixed cellWithTitle(string $title)
 * @method mixed cellWithLabelTextOrId(string $labelTextOrId)
 * @method mixed cellsWithId(string $id)
 * @method mixed cellsWithLabel(string $label)
 * @method mixed cellsLabelled(string $label)
 * @method mixed cellsWithName(string $name)
 * @method mixed cellsNamed(string $name)
 * @method mixed cellsWithClass(string $class)
 * @method mixed cellsWithPlaceholder(string $text)
 * @method mixed cellsWithTitle(string $title)
 * @method mixed cellsWithLabelTextOrId(string $labelTextOrId)
 * @method mixed dropdownWithId(string $id)
 * @method mixed dropdownWithLabel(string $label)
 * @method mixed dropdownLabelled(string $label)
 * @method mixed dropdownWithName(string $name)
 * @method mixed dropdownNamed(string $name)
 * @method mixed dropdownWithClass(string $class)
 * @method mixed dropdownWithPlaceholder(string $text)
 * @method mixed dropdownWithTitle(string $title)
 * @method mixed dropdownWithLabelTextOrId(string $labelTextOrId)
 * @method mixed dropdownsWithId(string $id)
 * @method mixed dropdownsWithLabel(string $label)
 * @method mixed dropdownsLabelled(string $label)
 * @method mixed dropdownsWithName(string $name)
 * @method mixed dropdownsNamed(string $name)
 * @method mixed dropdownsWithClass(string $class)
 * @method mixed dropdownsWithPlaceholder(string $text)
 * @method mixed dropdownsWithTitle(string $title)
 * @method mixed dropdownsWithLabelTextOrId(string $labelTextOrId)
 * @method mixed elementById(string $id)
 * @method mixed elementWithId(string $id)
 * @method mixed elementWithLabel(string $label)
 * @method mixed elementLabelled(string $label)
 * @method mixed elementWithName(string $name)
 * @method mixed elementNamed(string $name)
 * @method mixed elementWithClass(string $class)
 * @method mixed elementWithPlaceholder(string $text)
 * @method mixed elementWithTitle(string $title)
 * @method mixed elementByLabelIdOrName(string $text)
 * @method mixed elementWithLabelTextOrId(string $labelTextOrId)
 * @method mixed elementByXpath(string $xpath)
 * @method mixed elementsWithId(string $id)
 * @method mixed elementsWithLabel(string $label)
 * @method mixed elementsLabelled(string $label)
 * @method mixed elementsWithName(string $name)
 * @method mixed elementsNamed(string $name)
 * @method mixed elementsWithClass(string $class)
 * @method mixed elementsWithPlaceholder(string $text)
 * @method mixed elementsWithTitle(string $title)
 * @method mixed elementsWithLabelTextOrId(string $labelTextOrId)
 * @method mixed fieldWithId(string $id)
 * @method mixed fieldWithLabel(string $label)
 * @method mixed fieldLabelled(string $label)
 * @method mixed fieldWithName(string $name)
 * @method mixed fieldNamed(string $name)
 * @method mixed fieldWithClass(string $class)
 * @method mixed fieldWithPlaceholder(string $text)
 * @method mixed fieldWithTitle(string $title)
 * @method mixed fieldWithLabelTextOrId(string $labelTextOrId)
 * @method mixed fieldsWithId(string $id)
 * @method mixed fieldsWithLabel(string $label)
 * @method mixed fieldsLabelled(string $label)
 * @method mixed fieldsWithName(string $name)
 * @method mixed fieldsNamed(string $name)
 * @method mixed fieldsWithClass(string $class)
 * @method mixed fieldsWithPlaceholder(string $text)
 * @method mixed fieldsWithTitle(string $title)
 * @method mixed fieldsWithLabelTextOrId(string $labelTextOrId)
 * @method mixed headingWithId(string $id)
 * @method mixed headingWithLabel(string $label)
 * @method mixed headingLabelled(string $label)
 * @method mixed headingWithName(string $name)
 * @method mixed headingNamed(string $name)
 * @method mixed headingWithClass(string $class)
 * @method mixed headingWithPlaceholder(string $text)
 * @method mixed headingWithTitle(string $title)
 * @method mixed headingWithLabelTextOrId(string $labelTextOrId)
 * @method mixed headingsWithId(string $id)
 * @method mixed headingsWithLabel(string $label)
 * @method mixed headingsLabelled(string $label)
 * @method mixed headingsWithName(string $name)
 * @method mixed headingsNamed(string $name)
 * @method mixed headingsWithClass(string $class)
 * @method mixed headingsWithPlaceholder(string $text)
 * @method mixed headingsWithTitle(string $title)
 * @method mixed headingsWithLabelTextOrId(string $labelTextOrId)
 * @method mixed linkWithId(string $id)
 * @method mixed linkWithLabel(string $label)
 * @method mixed linkLabelled(string $label)
 * @method mixed linkWithName(string $name)
 * @method mixed linkNamed(string $name)
 * @method mixed linkWithClass(string $class)
 * @method mixed linkWithPlaceholder(string $text)
 * @method mixed linkWithTitle(string $title)
 * @method mixed linkWithLabelTextOrId(string $labelTextOrId)
 * @method mixed linksWithId(string $id)
 * @method mixed linksWithLabel(string $label)
 * @method mixed linksLabelled(string $label)
 * @method mixed linksWithName(string $name)
 * @method mixed linksNamed(string $name)
 * @method mixed linksWithClass(string $class)
 * @method mixed linksWithPlaceholder(string $text)
 * @method mixed linksWithTitle(string $title)
 * @method mixed linksWithLabelTextOrId(string $labelTextOrId)
 * @method mixed orderedlistWithId(string $id)
 * @method mixed orderedlistWithLabel(string $label)
 * @method mixed orderedlistLabelled(string $label)
 * @method mixed orderedlistWithName(string $name)
 * @method mixed orderedlistNamed(string $name)
 * @method mixed orderedlistWithClass(string $class)
 * @method mixed orderedlistWithPlaceholder(string $text)
 * @method mixed orderedlistWithTitle(string $title)
 * @method mixed orderedlistWithLabelTextOrId(string $labelTextOrId)
 * @method mixed spanWithId(string $id)
 * @method mixed spanWithLabel(string $label)
 * @method mixed spanLabelled(string $label)
 * @method mixed spanWithName(string $name)
 * @method mixed spanNamed(string $name)
 * @method mixed spanWithClass(string $class)
 * @method mixed spanWithPlaceholder(string $text)
 * @method mixed spanWithTitle(string $title)
 * @method mixed spanWithLabelTextOrId(string $labelTextOrId)
 * @method mixed unorderedlistWithId(string $id)
 * @method mixed unorderedlistWithLabel(string $label)
 * @method mixed unorderedlistLabelled(string $label)
 * @method mixed unorderedlistWithName(string $name)
 * @method mixed unorderedlistNamed(string $name)
 * @method mixed unorderedlistWithClass(string $class)
 * @method mixed unorderedlistWithPlaceholder(string $text)
 * @method mixed unorderedlistWithTitle(string $title)
 * @method mixed unorderedlistWithLabelTextOrId(string $labelTextOrId)
 * @method mixed intoElement($element)
 * @method mixed fromElement($element)
 *
 * @method mixed theFieldLabelled(string $label)
 * @method mixed fromFieldWithId(string $id)
 * @method void  intoFieldWithId(string $id)
 *
 * @category  Libraries
 * @package   Storyplayer/BrowserLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class SingleElementAction extends BaseElementAction
{
    protected $action;
    protected $actionDesc;

    /**
     * @param \Closure $action
     * @param string $actionDesc
     */
    public function __construct($action, $actionDesc, $baseElement = null)
    {
        parent::__construct($baseElement);

        $this->action      = $action;
        $this->actionDesc  = $actionDesc;
    }

    /**
     * @param  string $methodName
     * @param  array $methodArgs
     * @return mixed
     */
    public function __call($methodName, $methodArgs)
    {
        // retrieve the element, using the fake method name to figure out
        // which element the caller is looking for
        $element = $this->retrieveElement($methodName, $methodArgs);

        // now that we have our element, let's apply the action to it
        $action = $this->action;
        $return = $action($element, $methodArgs[0], $methodName);

        // all done
        return $return;
    }
}
