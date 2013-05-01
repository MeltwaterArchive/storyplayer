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
 * @package   Stone/HttpLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
namespace DataSift\Stone\HttpLib\Transports;

use Exception;
use DataSift\Stone\ExceptionsLib\LegacyErrorCatcher;
use DataSift\Stone\HttpLib\HttpClientConnection;
use DataSift\Stone\HttpLib\HttpClientRequest;
use DataSift\Stone\HttpLib\HttpClientResponse;

/**
 * Support for talking to a HTTP server via WebSockets
 *
 * Relies on our in-house websockets extension for PHP (which we haven't
 * open-sourced at this time)
 *
 * @category  Libraries
 * @package   Stone/HttpLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */

class WsFrame
{
    /**
     * The 'FIN' bit when set
     */
    const FIN_TRUE = 128;

    /**
     * The 'FIN' bit when unset
     */
    const FIN_FALSE = 0;

    /**
     * Opcode 0: frame is a continuation frame
     */
    const OPCODE_CONTINUATION = 0;

    /**
     * Opcode 1: frame contains text payload
     */
    const OPCODE_TEXT = 1;

    /**
     * Opcode 2: frame contains binary payload
     */
    const OPCODE_BINARY = 2;

    /**
     * Opcode 8: frame is a 'close' control frame
     */
    const OPCODE_CLOSE = 8;

    /**
     * Opcode 9: frame is a 'ping' control frame
     */
    const OPCODE_PING = 9;

    /**
     * Opcode 10: frame is a 'pong' control frame
     */
    const OPCODE_PONG = 10;

    /**
     * The 'MASK' bit when set
     */
    const MASK_TRUE = 128;

    /**
     * The 'MASK' bit when unset
     */
    const MASK_FALSE = 0;

    /**
     * Bitmask needed to extract the 'FIN' bit
     */
    const BITMASK_FIN = 128;

    /**
     * Bitmask needed to extract the RSV bits
     */
    const BITMASK_RSV = 112; // 64+32+16

    /**
     * Bitmask needed to extract the OPCODE bits
     */
    const BITMASK_OPCODE = 15; // 8+4+2+1

    /**
     * Bitmask needed to extract the 'MASK' bit
     */
    const BITMASK_MASK = 128;

    /**
     * Bitmask needed to extract the first byte of the PAYLOAD length
     */
    const BITMASK_PAYLOAD = 127;

    /**
     * Max value of the first byte of the PAYLOAD length if PAYLOAD length is
     * just the one byte in size
     */
    const PAYLOAD_5BIT = 125;

    /**
     * Value of the first byte of the PAYLOAD length if the PAYLOAD length is
     * stored as a 16-bit number
     */
    const PAYLOAD_16BIT = 126;

    /**
     * Value of the first byte of the PAYLOAD length if the PAYLOAD length is
     * stored as a 64-bit number
     */
    const PAYLOAD_64BIT = 127;

    /**
     * Current value of the FIN bit.
     *
     * Will be one of the self::FIN_* values
     *
     * @var int
     */
    protected $fin = 0;

    /**
     * Current value of the RSV bits
     *
     * @var int
     */
    protected $rsv = 0;

    /**
     * Current value of the OPCODE bits.
     *
     * Will be one of the self::OPCODE_* values
     *
     * @var int
     */
    protected $opcode = null;

    /**
     * Current value of the MASK bit
     *
     * Will be one of the self::MASK_* values
     *
     * @var int
     */
    protected $mask = 0;

    /**
     * The 4 byte nonce used to 'mask' the payload data
     *
     * @var string
     */
    protected $maskingKey = null;

    /**
     * The data used by a WS extension
     *
     * @var string
     */
    protected $extensionData = null;

    /**
     * The data being sent in this frame from the application
     * @var string
     */
    protected $applicationData = null;

    /**
     * The size of the current payload
     *
     * Exists simply to assist with debugging this class
     *
     * @var int
     */
    protected $payloadLen = 0;

    /**
     * The encoded frame, ready for transmission over the wire
     * @var string
     */
    protected $frame = null;

    // =========================================================================
    //
    // Fluent interface to create the frame programatically
    //
    // -------------------------------------------------------------------------

    /**
     * We are creating a continuation frame
     *
     * @return WsFrame
     */
    public function initAsContinuationFrame()
    {
        $this->opcode = self::OPCODE_CONTINUATION;

        // make sure any cached frame is invalidated
        $this->frame = null;

        // by default, we assume this is the last frame
        $this->fin = self::FIN_TRUE;

        // fluent interface support
        return $this;
    }

    /**
     * We are creating a text frame
     *
     * @return WsFrame
     */
    public function initAsTextFrame()
    {
        $this->opcode = self::OPCODE_TEXT;

        // make sure any cached frame is invalidated
        $this->frame = null;

        // by default, we assume this is the last frame
        $this->fin = self::FIN_TRUE;

        // fluent interface support
        return $this;
    }

    /**
     * We are creating a binary frame
     *
     * @return WsFrame
     */
    public function initAsBinaryFrame()
    {
        $this->opcode = self::OPCODE_BINARY;

        // make sure any cached frame is invalidated
        $this->frame = null;

        // by default, we assume this is the last frame
        $this->fin = self::FIN_TRUE;

        // fluent interface support
        return $this;
    }

    /**
     * We are creating a close frame
     *
     * @return WsFrame
     */
    public function initAsCloseFrame()
    {
        $this->opcode = self::OPCODE_CLOSE;

        // make sure any cached frame is invalidated
        $this->frame = null;

        // by default, we assume this is the last frame
        $this->fin = self::FIN_TRUE;

        // fluent interface support
        return $this;
    }

    /**
     * We are creating a ping frame
     *
     * @return WsFrame
     */
    public function initAsPingFrame()
    {
        $this->opcode = self::OPCODE_PING;

        // make sure any cached frame is invalidated
        $this->frame = null;

        // by default, we assume this is the last frame
        $this->fin = self::FIN_TRUE;

        // fluent interface support
        return $this;
    }

    /**
     * We are creating a pong frame
     *
     * @return WsFrame
     */
    public function initAsPongFrame()
    {
        $this->opcode = self::OPCODE_PONG;

        // make sure any cached frame is invalidated
        $this->frame = null;

        // by default, we assume this is the last frame
        $this->fin = self::FIN_TRUE;

        // fluent interface support
        return $this;
    }

    /**
     * tell the other end that we'll be sending an additional frame or more
     * after this one
     */
    public function willBeMoreFrames()
    {
        // next frame will be a continuation frame
        $this->fin = self::FIN_FALSE;
    }

    /**
     * This frame is being sent by the client
     */
    public function willSendFromClient()
    {
        $this->mask = self::MASK_TRUE;
        $this->maskingKey = $this->generateMaskingKey();

        // make sure any cached frame is invalidated
        $this->frame = null;

        // fluent interface support
        return $this;
    }

    /**
     * This frame is being sent by the server
     */
    public function willSendFromServer()
    {
        $this->mask = self::MASK_FALSE;
        $this->maskingKey = null;

        // make sure any cached frame is invalidated
        $this->frame = null;

        // fluent interface support
        return $this;
    }

    /**
     * Set the three RSV bits
     *
     * At the time of writing, these bits are reserved for future extensions
     * to websockets.
     *
     * @param int $value
     */
    public function withRSVValue($value)
    {
        $this->rsv = $value;

        // make sure any cached frame is invalidated
        $this->frame = null;

        // fluent interface support
        return $this;
    }

    /**
     * Set the extension data to transmit
     *
     * @param string $data
     * @return WsFrame
     */
    public function withExtensionData($data)
    {
        $this->extensionData = $data;

        // how large is the payload now?
        $this->updatePayloadLen();

        // make sure any cached frame is invalidated
        $this->frame = null;

        // fluent interface support
        return $this;
    }

    /**
     * Set the application data to transmit
     *
     * @param string $data
     * @return WsFrame
     */
    public function withApplicationData($data)
    {
        $this->applicationData = $data;

        // how large is the payload now?
        $this->updatePayloadLen();

        // make sure any cached frame is invalidated
        $this->frame = null;

        // fluent interface support
        return $this;
    }

    /**
     * recalcuate the current payload length
     */
    protected function updatePayloadLen()
    {
        $this->payloadLen = strlen($this->extensionData) + strlen($this->applicationData);
    }

    // =========================================================================
    //
    // Support for decoding a frame from data we have received
    //
    // -------------------------------------------------------------------------

    /**
     * Initialise this object, using a frame that we're received on the wire
     *
     * @param string $data
     */
    public function initFromData($data)
    {
        // decode a received frame ... hopefully!
        $this->frame = $data;

        // byte 1
        //
        // first bit is the FIN bit
        $this->fin = ord($data{0} & chr(self::BITMASK_FIN));

        // next three bits are the RSV bits
        $this->rsv = (ord($data{0} & chr(self::BITMASK_RSV))) >> 4;

        // next four bits are the opcode
        $this->opcode = ord($data{0} & chr(self::BITMASK_OPCODE));

        // byte 2 (and possibly more)
        //
        // first bit is the mask bit
        $this->mask = ord($data{1} & chr(self::BITMASK_MASK));

        $payloadByte1 = ord($data{1} & chr(self::BITMASK_PAYLOAD));
        if ($payloadByte1 < self::PAYLOAD_16BIT)
        {
            // 5-bit payload length
            //var_dump('5-bit payload');
            $payloadLen = $payloadByte1;
            //var_dump($payloadLen);
            $byte = 2;
        }
        else if ($payloadByte1 == self::PAYLOAD_16BIT)
        {
            //var_dump('16-bit payload');
            $parts = unpack("nlen", substr($data, 2, 2));
            $payloadLen = $parts['len'];
            //var_dump($payloadLen);
            $byte = 4;
        }
        else // self::PAYLOAD_64BIT
        {
            // var_dump('64-bit payload');
            $parts = unpack("Nlen1/Nlen2", substr($data, 2, 8));
            $payloadLen = $parts['len1'] * pow(2,32) + $parts['len2'];
            // var_dump($payloadLen);
            $byte = 10;
        }

        // masking key
        if ($this->isMaskedFrame())
        {
            $this->maskingKey = substr($data, $byte, 4);
            $byte += 4;
        }

        // payload
        if ($payloadLen > 0)
        {
            $this->applicationData = substr($data, $byte, $payloadLen);

            if ($this->isMaskedFrame())
            {
                $this->applicationData = $this->maskPayload($this->applicationData, $this->maskingKey);
            }
        }
        $this->updatePayloadLen();

        $byte += $payloadLen;

        // in theory, all done
        //
        // do we have any data left?
        if ($byte != strlen($data))
        {
            var_dump("expected frame of " . $byte . '; received frame of ' . strlen($data));
        }

        // all done
    }

    /**
     * work out how long the rest of the frame is, by reading the first three
     * bytes
     *
     * this helps the WsTransport figure out how to read a frame off the wire
     *
     * @param type $data
     *      the first two bytes off the wire for this frame
     * @return int
     *      the number of bytes to read off the wire to retrieve the rest
     *      of the frame's header data
     */
    public function determineRemainingHeaderBytes($data)
    {
        // if the frame on the wire is masked, it affects the total length
        // of the transmitted frame
        $mask = ord($data{1} & chr(self::BITMASK_MASK));

        $payloadByte1 = ord($data{1} & chr(self::BITMASK_PAYLOAD));
        if ($payloadByte1 < self::PAYLOAD_16BIT)
        {
            // 5-bit payload length
            $payloadBytes = 0;
        }
        else if ($payloadByte1 == self::PAYLOAD_16BIT)
        {
            // 16-bit payload len
            $payloadBytes = 2;
        }
        else // self::PAYLOAD_64BIT
        {
            // 64-bit payload length
            $payloadBytes = 8;
        }

        if ($mask)
        {
            // header is the size of the payload bytes, plus the size of the
            // mask bytes
            return $payloadBytes + 4;
        }
        else
        {
            return $payloadBytes;
        }
    }

    /**
     * work out how long the rest of the frame is, by looking at all the bytes
     * that make up the header of the frame
     *
     * this helps the WsTransport figure out how to read a frame off the wire
     *
     * @param string $data
     *      all of the bytes that make up the frame's header
     * @return int
     *      the number bytes to read to pull the frame's payload off the wire
     */
    public function determineRemainingFrameBytes($data)
    {
        $payloadByte1 = ord($data{1} & chr(self::BITMASK_PAYLOAD));
        if ($payloadByte1 < self::PAYLOAD_16BIT)
        {
            // 5-bit payload length
            $payloadLen = $payloadByte1;
        }
        else if ($payloadByte1 == self::PAYLOAD_16BIT)
        {
            // 16-bit payload len
            $parts = unpack("nlen", substr($data, 2, 2));
            $payloadLen = $parts['len'];
        }
        else // self::PAYLOAD_64BIT
        {
            // 64-bit payload length
            $parts = unpack("Nlen1/Nlen2", substr($data, 2, 8));
            $payloadLen = $parts['len1'] * pow(2,32) + $parts['len2'];
        }

        return $payloadLen;
    }

    // =========================================================================
    //
    // Support for inspecting a frame
    //
    // -------------------------------------------------------------------------

    /**
     * Is this frame a final frame?
     *
     * @return boolean
     */
    public function isFin()
    {
        return ($this->fin == self::FIN_TRUE);
    }

    /**
     * Is this frame a continuation frame?
     *
     * @return boolean
     */
    public function isContinuationFrame()
    {
        return ($this->opcode == self::OPCODE_CONTINUATION);
    }

    /**
     * Is this frame a text frame?
     *
     * @return boolean
     */
    public function isTextFrame()
    {
        return ($this->opcode == self::OPCODE_TEXT);
    }

    /**
     * Is this frame a binary frame?
     *
     * @return boolean
     */
    public function isBinaryFrame()
    {
        return ($this->opcode == self::OPCODE_BINARY);
    }

    /**
     * Is this frame a close frame?
     *
     * @return boolean
     */
    public function isCloseFrame()
    {
        return ($this->opcode == self::OPCODE_CLOSE);
    }

    /**
     * Is this frame a ping frame?
     *
     * @return boolean
     */
    public function isPingFrame()
    {
        return ($this->opcode == self::OPCODE_PING);
    }

    /**
     * Is this frame a pong frame?
     *
     * @return boolean
     */
    public function isPongFrame()
    {
        return ($this->opcode == self::OPCODE_PONG);
    }

    /**
     * Is this frame masked?
     *
     * Note: we *always* store both the extensionData and the applicationData
     * in their unmasked form, and make sure that we do the right thing if
     * anyone asks for a copy of the frame to transmit on the wire
     *
     * @return boolean
     */
    public function isMaskedFrame()
    {
        return ($this->mask == self::MASK_TRUE);
    }

    /**
     * Return the RSV bits
     *
     * @return int
     */
    public function getRSV()
    {
        return $this->rsv;
    }

    /**
     * What is the opcode that we've had?
     * @return int
     */
    public function getOpcode()
    {
        return $this->opcode;
    }

    /**
     * How large is the combined extensionData & applicationData?
     *
     * @return int
     */
    public function getPayloadLen()
    {
        return (strlen($this->extensionData) + strlen($this->applicationData));
    }

    /**
     * What is the extension data inside this frame?
     *
     * @return string
     */
    public function getExtensionData()
    {
        return $this->extensionData;
    }

    /**
     * What is the application data inside this frame?
     *
     * @return string
     */
    public function getApplicationData()
    {
        return $this->applicationData;
    }

    // =========================================================================
    //
    // Support for turning this object into a frame we can transmit
    //
    // -------------------------------------------------------------------------

    /**
     * turn the frame data into the binary string to transmit
     */
    public function __toString()
    {
        // do we already have the frame?
        if (isset($this->frame))
        {
            return $this->frame;
        }

        // no, we do not ... we need to build it
        //
        // build first byte
        $frame = chr($this->fin + ($this->rsv << 4) + $this->opcode);

        // build the second byte
        //
        // if the payload is large, we also have to build bytes 3-7, or bytes 3-10
        $payloadLen = $this->getPayloadLen();
        if ($payloadLen < 126)
        {
            $frame .= chr($this->mask + $payloadLen);
        }
        else if ($payloadLen < pow(2, 16))
        {
            $frame .= chr($this->mask + self::PAYLOAD_16BIT);
            $frame .= pack("n", $payloadLen);
        }
        else
        {
            $frame .= chr($this->mask + self::PAYLOAD_64BIT);
            $frame .= pack("N", $payloadLen / pow(2, 32)) . pack("N", $payloadLen % pow(2,32));
        }

        // add in the mask
        if ($this->mask)
        {
            $frame .= $this->maskingKey;
        }

        // add in the payload
        if ($payloadLen > 0)
        {
            $payload = $this->extensionData . $this->applicationData;
            if ($this->mask)
            {
                $payload = $this->maskPayload($payload, $this->maskingKey);
            }
            $frame .= $payload;
        }

        // the frame is complete
        // cache it, just in case we want it more than once for some reason
        $this->frame = $frame;

        return $frame;
    }

    /**
     * Generate 4 bytes of random data to use in the masking key for this frame
     *
     * @return string
     */
    public function generateMaskingKey()
    {
        // step 1: create 4 bytes of random data
        $byteLen = 4;

        if (@is_readable('/dev/urandom'))
        {
           $fp = fopen('/dev/urandom', 'r');
           $nonceBytes = fread($fp, $byteLen);
           fclose($fp);
        }
        else
        {
            $nonceBytes = '';
            for ($i = 0; $i < $byteLen; $i++)
            {
                $nonceBytes += chr(mt_rand(0, 255));
            }
        }

        // we have our data
        return $nonceBytes;
    }

    /**
     * Mask / demask the data payload
     *
     * The websockets masking algorithm is designed so that, if applied twice
     * to the same data, you get back to your original data.
     *
     * @param string $payload the data to be masked
     * @param string $mask the 4-byte mask to use
     *
     * @return the masked / demasked payload
     */
    public function maskPayload($payload, $mask)
    {
        $return = '';
        $payloadLen = strlen($payload);
        for ($i = 0; $i < $payloadLen ; $i++)
        {
            $return .= $payload{$i} ^ $mask{$i % 4};
        }

        return $return;
    }
}
