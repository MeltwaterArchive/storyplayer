<?php

/**
 * WebDriver - Client for Selenium 2 (a.k.a WebDriver)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @category  Libraries
 * @package   BrowserMobProxy
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2012-present MediaSift Ltd
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://www.datasift.com
 */

namespace DataSift\WebDriver;

/**
 * Helper class defining all the non-text keys that can be sent to the
 * web browser, as listed in the Json Wire Protocol
 *
 * @category Libraries
 * @package  WebDriver
 * @license  http://www.apache.org/licenses/LICENSE-2.0
 * @link     http://www.datasift.com
 * @link     https://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/value
 */

class WebDriverKeys
{
	const NULL_KEY   = '&#xE000;';
	const CANCEL_KEY = '&#xE001;';
	const HELP_KEY   = '&#xE002;';
	const BACKSPACE_KEY = '&#xE003;';
	const TAB_KEY    = '&#xE004;';
	const CLEAR_KEY = '&#xE005;';
	const RETURN_KEY = '&#xE006;';
	const ENTER_KEY = '&#xE007;';
	const SHIFT_KEY = '&#xE008;';
	const CONTROL_KEY = '&#xE009;';
	const ALT_KEY = '&#xE00A;';
	const PAUSE_KEY = '&#xE00B;';
	const ESC_KEY = '&#xE00C;';
	const SPACE_KEY = '&#xE00D;';
	const PGUP_KEY = '&#xE00E;';
	const PGDN_KEY = '&#xE00F;';
	const END_KEY = '&#xE010;';
	const HOME_KEY = '&#xE011;';
	const LEFT_ARROW_KEY = '&#xE012;';
	const UP_ARROW_KEY = '&#xE013;';
	const RIGHT_ARROW_KEY = '&#xE014;';
	const DOWN_ARROW_KEY = '&#xE015;';
	const INSERT_KEY = '&#x0E16;';
	const DELETE_KEY = '&#xE017;';
	const SEMICOLON_KEY = '&#xE018;';
	const EQUALS_KEY = '&#xE019;';
	const NUMPAD_0_KEY = '&#xE01A;';
	const NUMPAD_1_KEY = '&#xE01B;';
	const NUMPAD_2_KEY = '&#xE01C;';
	const NUMPAD_3_KEY = '&#xE01D;';
	const NUMPAD_4_KEY = '&#xE01E;';
	const NUMPAD_5_KEY = '&#xE01F;';
	const NUMPAD_6_KEY = '&#xE020;';
	const NUMPAD_7_KEY = '&#xE021;';
	const NUMPAD_8_KEY = '&#xE022;';
	const NUMPAD_9_KEY = '&#xE023;';
	const NUMPAD_MULTIPLY_KEY = '&#xE024;';
	const NUMPAD_ADD_KEY = '&#xE025;';
	const SEPARATOR_KEY = '&#xE026;';
	const NUMPAD_SUBTRACT_KEY = '&#xE027;';
	const NUMPAD_DECIMAL_KEY = '&#xE028;';
	const NUMPAD_DIVIDE_KEY = '&#xE029;';

	const F1_KEY = '&#xE031;';
	const F2_KEY = '&#xE032;';
	const F3_KEY = '&#xE033;';
	const F4_KEY = '&#xE034;';
	const F5_KEY = '&#xE035;';
	const F6_KEY = '&#xE036;';
	const F7_KEY = '&#xE037;';
	const F8_KEY = '&#xE038;';
	const F9_KEY = '&#xE039;';
	const F10_KEY = '&#xE03A;';
	const F11_KEY = '&#xE03B;';
	const F12_KEY = '&#xE03C;';
	const META_KEY = '&#xE03D;';
}