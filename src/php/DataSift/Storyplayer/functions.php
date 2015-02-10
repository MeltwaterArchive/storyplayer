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

use DataSift\Storyplayer\PlayerLib\Story;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\Prose\E5xx_ActionFailed;
use DataSift\Storyplayer\Prose\E5xx_ProseException;

/**
 * return the first element in an array
 *
 * this function avoids reset()ing the array, so it will not mess with
 * any iteration that you may currently be part-way through
 *
 * @param  array $arrayToSearch
 *         the array to get the first element of
 * @return mixed
 *         the first element of $array, or NULL if the array is empty
 */
function first($arrayToSearch)
{
	if (!is_array($arrayToSearch)) {
		return null;
	}

	if (count($arrayToSearch) == 0) {
		return null;
	}

	$keys = array_keys($arrayToSearch);
	$key = reset($keys);

	return $arrayToSearch[$key];
}

/**
 * iterate over all hosts that match the given role
 *
 * @param  string $roleName
 *         The role that we want
 *
 * @return string
 *         a hostname that matches the role
 */
function hostWithRole($roleName)
{
	// special case
	if ($roleName instanceof StoryTeller) {
		throw new E5xx_ActionFailed(__METHOD__, "first param to hostWithRole() is no longer \$st");
	}

	// shorthand
	$st = StoryTeller::instance();

	$listOfHosts = $st->fromRolesTable()->getDetailsForRole($roleName);
	if (!count(get_object_vars($listOfHosts))) {
		throw new E5xx_ActionFailed(__METHOD__, "unknown role '{$roleName}' or no hosts for that role");
	}

	// what are we doing?
	$log = $st->startAction("for each host with role '{$roleName}' ... ");

	foreach ($listOfHosts as $hostDetails) {
		yield($hostDetails->name);
	}

	// all done
	$log->endAction();
}

/**
 * Create a new story object
 *
 * @param  string $category the category that the story belongs to
 * @return Story            the new story object to use
 */
function newStoryFor($category)
{
	$story = new Story();
	$story->setCategory($category);

	// our output reports may need to know which file the story itself
	// is defined in
	$story->determineStoryFilename();

	return $story;
}

/**
 * Attempt an action, and if it fails, swallow the failure
 *
 * @param  callback $callback the action(s) to attempt
 * @return void
 */
function tryTo($callback) {
	try {
		$callback();
	}
	catch (E5xx_ProseException $e) {
		// do nothing
	}
}
