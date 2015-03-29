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
 * @package   Storyplayer/Phases
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Phases;

use Exception;
use Prose\E5xx_ActionFailed;
use Prose\E5xx_ExpectFailed;
use Prose\E5xx_NotImplemented;
use Prose\E5xx_StoryCannotRun;

/**
 * the TestShouldRun phase
 *
 * @category  Libraries
 * @package   Storyplayer/Phases
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

class TestCanRunCheckPhase extends StoryPhase
{
    protected $sequenceNo = 1;

    public function doPhase($story)
    {
        // shorthand
        $st          = $this->st;
        $storyResult = $story->getResult();

        // keep track of what happens with the action
        $phaseResult = $this->getNewPhaseResult();

        // do we have anything to do?
        if (!$story->hasTestCanRunCheck())
        {
            $phaseResult->setContinuePlaying(
                $phaseResult::COMPLETED,
                "story can always run"
            );
            return $phaseResult;
        }

        // get the callback to call
        $callbacks = $story->getTestCanRunCheck();

        // make the call
        try {
            foreach ($callbacks as $callback) {
                $canRun = call_user_func($callback, $st);

                // what did the callback tell us?
                //
                // we treat TWO results as valid reports that the test
                // should be skipped:
                //
                // 1: FALSE
                // 2: an error message to write to the outputs
                //
                // ANYTHING else is treated as permission to continue
                // and potentially run the rest of the story
                if ($canRun === false || is_string($canRun)) {
                    $msg = "test reported that it cannot run";
                    if (is_string($canRun)) {
                        $msg = $canRun;
                    }
                    $phaseResult->setSkipPlaying(
                        $phaseResult::CANNOTRUN,
                        $msg
                    );
                    $storyResult->setStoryHasBeenSkipped($phaseResult);
                    return $phaseResult;
                }
            }

            // if we get here, then all is well
            $phaseResult->setContinuePlaying();
        }
        // if an action fails, we treat that as a fault, and mark the
        // story as failed
        catch (E5xx_ActionFailed $e) {
            $phaseResult->setPlayingFailed(
                $phaseResult::FAILED,
                $e->getMessage(),
                $e
            );
            $storyResult->setStoryHasFailed($phaseResult);
        }
        // if an expect fails, we treat that as meaning that the story
        // cannot run
        catch (E5xx_ExpectFailed $e) {
            $phaseResult->setSkipPlaying(
                $phaseResult::CANNOTRUN,
                $e->getMessage(),
                $e
            );
            $storyResult->setStoryHasBeenSkipped($phaseResult);
        }
        // if any of the modules used are incomplete, deal with that too
        catch (E5xx_NotImplemented $e) {
            $phaseResult->setPlayingFailed(
                $phaseResult::INCOMPLETE,
                $e->getMessage(),
                $e
            );
            $storyResult->setStoryIsIncomplete($phaseResult);
        }
        catch (E5xx_StoryCannotRun $e) {
            $phaseResult->setSkipPlaying(
                $phaseResult::CANNOTRUN,
                $e->getMessage()
            );
            $storyResult->setStoryHasBeenSkipped($phaseResult);
        }
        catch (Exception $e)
        {
            // something went wrong ... the test cannot continue
            $phaseResult->setPlayingFailed(
                $phaseResult::ERROR,
                $e->getMessage(),
                $e
            );
            $storyResult->setStoryHasFailed($phaseResult);
        }

        // all done
        return $phaseResult;
    }
}