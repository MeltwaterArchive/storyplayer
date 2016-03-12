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
 * @package   Storyplayer/Modules/Device
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv3\Modules;

use Storyplayer\SPv3\Modules\Aws\ExpectsEc2Image;
use Storyplayer\SPv3\Modules\Aws\FromAws;
use Storyplayer\SPv3\Modules\Aws\FromEc2;
use Storyplayer\SPv3\Modules\Aws\FromEc2Instance;
use Storyplayer\SPv3\Modules\Aws\UsingEc2;
use Storyplayer\SPv3\Modules\Aws\UsingEc2Instace;

class Aws
{
    /**
     * make sure that an EC2 image is as you expect it
     * @return ExpectsEc2Image
     */
    public static function expectsEc2Image()
    {
        return new ExpectsEc2Image();
    }

    /**
     * get information from an AWS account
     * @return FromAws
     */
    public static function fromAws()
    {
        return new FromAws();
    }

    /**
     * get information from your EC2 service
     * @return FromEc2
     */
    public static function fromEc2()
    {
        return new FromEc2;
    }

    /**
     * get information from an EC2 instance
     *
     * @param  string $hostId
     *         the ID of the EC2 instance to inspect
     * @return FromEc2Instance
     */
    public static function fromEc2Instance($hostId)
    {
        return new FromEc2Instance($hostId);
    }

    /**
     * perform an action using your EC2 service
     * @return UsingEc2
     */
    public static function usingEc2()
    {
        return new UsingEc2();
    }

    /**
     * perform an action on an EC2 instance
     *
     * @param  string $hostId
     *         the ID of the EC2 instance to act on
     * @return UsingEc2Instance
     */
    public static function usingEc2Instance($hostId)
    {
        return new UsingEc2Instance($hostId);
    }
}
