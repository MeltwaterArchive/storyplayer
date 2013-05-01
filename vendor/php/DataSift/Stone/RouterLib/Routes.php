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
 * @package   Stone/RouterLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */

namespace DataSift\Stone\RouterLib;

use DataSift\Stone\LogLib\Log;

/**
 * A container for the routes that an application supports
 *
 * @category  Libraries
 * @package   Stone/RouterLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/stone
 */
class Routes
{
	/**
	 * our list of routes to support
	 * @var array
	 */
	protected $routes = array();

	const METHOD_GET  	= "GET";
	const METHOD_POST 	= "POST";
	const METHOD_PUT  	= "PUT";
	const METHOD_DELETE = "DELETE";

	/**
	 * constructor
	 *
	 * @param array $routes
	 *        a list of the routes that the application supports, for
	 *        bulk loading support
	 */
	public function __construct($routes = array())
	{
		$this->routes = $routes;
	}

	/**
	 * add an additional route to our list of known routes
	 *
	 * @param string $verb
	 *        the HTTP verb that this route applies to
	 * @param string $pattern
	 *        the regex to match for this route
	 * @param string $controller
	 *        the controller script to transfer control to
	 */
	public function addRoute($verb, $pattern, $controller)
	{
		if (!isset($this->routes[$verb])) {
			$this->routes[$verb] = array();
		}

		$this->routes[$verb][] = array (
			'pattern'    => $pattern,
			'controller' => $controller
		);
	}

	/**
	 * get the current list of supported routes
	 *
	 * @return array
	 */
	public function getRoutes()
	{
		return $this->routes;
	}

	/**
	 * get the current list of routes for a specific HTTP verb
	 *
	 * @param  string $verb
	 *         the HTTP verb to search for
	 * @return array
	 *         the list of supported routes for that verb
	 *
	 * 		   returns an empty array if there are no routes for that
	 * 		   HTTP verb
	 */
	public function getRoutesForVerb($verb)
	{
		// do we have any matching routes?
		if (isset($this->routes[$verb])) {
			return $this->routes[$verb];
		}

		// no routes
		return array();
	}
	/**
	 * set the list of routes, replacing any existing routes in our list
	 *
	 * @param array $routes
	 *        the new list of routes to track
	 */
	public function setRoutes($routes)
	{
		$this->routes = $routes;
	}
}