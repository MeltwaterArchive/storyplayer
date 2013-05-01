<?php

/**
 * Copyright (c) 2010 Etsy
 * Copyright (c) 2011-present Mediasift Ltd
 * All rights reserved.
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 *
 * @category  Libraries
 * @package   Stone/StatsLib
 * @author    Etsy
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2010 Etsy
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://datasift.github.io/stone
 * @link      https://github.com/etsy/statsd/blob/master/examples/php-example.php
 */

namespace DataSift\Stone\StatsLib;

/**
 * Sends statistics to the stats daemon over UDP
 *
 * This is derived from the original Etsy PHP example client
 *
 * @category  Libraries
 * @package   Stone/StatsLib
 * @author    Etsy
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2010 Etsy
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://datasift.github.io/stone
 * @link      https://github.com/etsy/statsd/blob/master/examples/php-example.php
 */
class BufferedStatsdClient
{
    /**
     * our wrapped StatsdClient
     * @var DataSift\Stone\StatsLib\StatsdClient
     */
    protected $client;

    /**
     * a list of the stats that we want to send to statsd
     * @var array
     */
    protected $stats = array();

    /**
     * constructor
     *
     * Setup the client, tell it where statsd is located
     *
     * @param string $host
     *        the hostname or IP address of the statsd instance to use
     * @param int $port
     *        the IPv4 port where statsd is listening
     */
    public function __construct($host = null, $port = null)
    {
        $this->client = new StatsdClient($host, $port);
    }

    /**
     * Log timing information
     *
     * @param string $stat
     *        The metric to in log timing info for.
     * @param float $time
     *        The ellapsed time (ms) to log
     * @param float|1 $sampleRate
     *        the rate (0-1) for sampling.
     */
    public function timing($stat, $time, $sampleRate=1) {
        if (!isset($this->stats[$stat])) {
            $this->stats[$stat] = array();
        }
        $this->stats[$stat][] = array("type" => 'ms', "value" => "$time");
    }

    /**
     * Increments one or more stats counters
     *
     * @param string|array $stats
     *        The metric(s) to increment.
     * @param float|1 $sampleRate
     *        the rate (0-1) for sampling.
     * @return boolean
     */
    public function increment($stats, $sampleRate=1) {
        $this->updateStats($stats, 1, $sampleRate);
    }

    /**
     * Decrements one or more stats counters.
     *
     * @param string|array $stats
     *        The metric(s) to decrement.
     * @param float|1
     *        $sampleRate the rate (0-1) for sampling.
     * @return boolean
     */
    public function decrement($stats, $sampleRate=1) {
        $this->updateStats($stats, -1, $sampleRate);
    }

    /**
     * Updates one or more stats counters by arbitrary amounts.
     *
     * @param string|array $stats
     *        The metric(s) to update. Should be either a string or array of metrics.
     * @param int|1 $delta
     *        The amount to increment/decrement each metric by.
     * @param float|1
     *        $sampleRate the rate (0-1) for sampling.
     * @return boolean
     */
    public function updateStats($stats, $delta=1, $sampleRate=1) {
        if (!is_array($stats)) {
            $stats = array($stats);
        }
        foreach($stats as $stat) {
            if (!isset($this->stats[$stat])) {
                $this->stats[$stat] = array("type" => 'c', "value" => 0);
            }

            $this->stats[$stat]['value'] = $this->stats[$stat]['value'] + $delta;
        }
    }

    /**
     * call this when you're ready to send the data
     *
     * @return void
     */
    public function send() {
        foreach ($this->stats as $name => $data) {
            $this->client->send(array($name => $data['value'].'|'. $data['type']));
        }

        // now, reset our list
        $this->stats = array();
    }
}
