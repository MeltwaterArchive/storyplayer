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
 * @package   Storyplayer/OsLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\OsLib;

use DataSift\Stone\ObjectLib\BaseObject;
use DataSift\Storyplayer\HostLib\SupportedHost;
use GanbaroDigital\TextTools\Filters\FilterColumns;
use GanbaroDigital\TextTools\Filters\FilterForMatchingString;

use Prose\E5xx_ActionFailed;

/**
 * work with CentOS 7.x guest operating system
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

abstract class Base_Centos7 extends Base_Centos5
{
    /**
     *
     * @param  HostDetails $hostDetails
     * @param  SupportedHost $host
     * @return string
     */
    public function determineIpAddress($hostDetails, SupportedHost $host)
    {
        // what are we doing?
        $log = usingLog()->startAction("query " . basename(__CLASS__) . " for IP address");

        if (empty($hostDetails->ifaces)) {
            // set default network interfaces
            $hostDetails->ifaces = array('eth1', 'eth0');
        }

        // how do we do this?
        foreach ($hostDetails->ifaces as $iface) {
            $command = "/sbin/ifconfig {$iface}";
            $result = $host->runCommandViaHostManager($hostDetails, $command);

            // NOTE: the above command will return the exit code 0 even if the interface is not found
            if ($result->didCommandFail() || (strpos($result->output, 'error fetching') !== false)) {
                // no interface found
                //
                // move on to the next interface to check
                continue;
            }

            // reduce the output down to an IP address
            $lines = explode("\n", $result->output);
            $lines = FilterForMatchingString::against($lines, 'inet ');
            $lines = FilterColumns::from($lines, '1', ' ');

            // do we have an IP address?
            if (!isset($lines[0]) || empty(trim(rtrim($lines[0])))) {
                // no, we do not
                continue;
            }

            // if we get here, then we have an IP address
            $ipAddress = trim($lines[0]);
            $log->endAction("IP address is '{$ipAddress}'");
            return $ipAddress;
        }

        // if we get here, we do not know what the IP address is
        $msg = "could not determine IP address";
        $log->endAction($msg);
        throw new E5xx_ActionFailed(__METHOD__, $msg);
    }
}