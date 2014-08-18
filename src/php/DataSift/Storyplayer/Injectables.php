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
 * @package   Storyplayer/Injectables
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer;

use Phix_Project\Injectables as BaseInjectables;

use DataSift\Storyplayer\Injectables\ActiveConfigSupport;
use DataSift\Storyplayer\Injectables\ActiveDeviceSupport;
use DataSift\Storyplayer\Injectables\ActiveSystemUnderTestConfigSupport;
use DataSift\Storyplayer\Injectables\ActiveTestEnvironmentConfigSupport;
use DataSift\Storyplayer\Injectables\AdditionalConfigsSupport;
use DataSift\Storyplayer\Injectables\CodeParserSupport;
use DataSift\Storyplayer\Injectables\DefaultConfigSupport;
use DataSift\Storyplayer\Injectables\DefaultSystemUnderTestName;
use DataSift\Storyplayer\Injectables\DefaultTestEnvironmentName;
use DataSift\Storyplayer\Injectables\KnownDevicesSupport;
use DataSift\Storyplayer\Injectables\KnownSystemsUnderTestSupport;
use DataSift\Storyplayer\Injectables\KnownTestEnvironmentsSupport;
use DataSift\Storyplayer\Injectables\OutputSupport;
use DataSift\Storyplayer\Injectables\PhaseLoaderSupport;
use DataSift\Storyplayer\Injectables\ProseLoaderSupport;
use DataSift\Storyplayer\Injectables\ReportLoaderSupport;
use DataSift\Storyplayer\Injectables\RuntimeConfigSupport;
use DataSift\Storyplayer\Injectables\StaticConfigManagerSupport;
use DataSift\Storyplayer\Injectables\StoryplayerConfigFilenameSupport;
use DataSift\Storyplayer\Injectables\StoryplayerConfigSupport;
use DataSift\Storyplayer\Injectables\TemplateEngineSupport;

/**
 * a container for common services and data, to avoid making them global
 *
 * @category  Libraries
 * @package   Storyplayer/Injectables
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Injectables extends BaseInjectables
{
	use ActiveConfigSupport;
	use ActiveDeviceSupport;
	use ActiveSystemUnderTestConfigSupport;
	use ActiveTestEnvironmentConfigSupport;
	use AdditionalConfigsSupport;
	use CodeParserSupport;
	use DefaultConfigSupport;
	use DefaultSystemUnderTestName;
	use DefaultTestEnvironmentName;
	use KnownDevicesSupport;
	use KnownSystemsUnderTestSupport;
	use KnownTestEnvironmentsSupport;
	use OutputSupport;
	use PhaseLoaderSupport;
	use ProseLoaderSupport;
	use ReportLoaderSupport;
	use RuntimeConfigSupport;
	use StaticConfigManagerSupport;
	use StoryplayerConfigFilenameSupport;
	use StoryplayerConfigSupport;
	use TemplateEngineSupport;
}
