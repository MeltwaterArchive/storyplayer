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
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\PlayerLib;

use Exception;

use DataSift\Storyplayer\ProseLib\E5xx_NoMatchingActions;
use DataSift\Storyplayer\ProseLib\ProseLoader;
use DataSift\Storyplayer\ProseLib\PageContext;
use DataSift\Storyplayer\StoryLib\Story;

use DataSift\Stone\HttpLib\HttpAddress;
use DataSift\Stone\Log\LogLib;
use DataSift\Stone\PathLib\PathTo;
use DataSift\Stone\ProcessLib\SubProcess;

use DataSift\BrowserMobProxy\BrowserMobProxyClient;
use DataSift\WebDriver\WebDriverClient;

/**
 * our main facilitation class
 *
 * all actions and tests inside a story are executed through an instance
 * of this class, making this class the StoryTeller :)
 *
 * @category  Libraries
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class StoryTeller
{
	/**
	 * the story that is being played
	 * @var Story
	 */
	private $story = null;

	private $storyContext = null;
	private $pageContext = null;
	private $checkpoint = null;

	private $webBrowser = null;
	private $webProxy = null;

	private $proseLoader = null;

	private $configLoader = null;

	/**
	 * [$actionLogger description]
	 * @var Datasift\Storyplayer\PlayerLib\ActionLogItem
	 */
	private $actionLogger;

	/**
	 * which of the steps is currently being executed?
	 * @var [type]
	 */
	private $currentPhase = null;

	public function __construct(Story $story)
	{
		// this is the story that will be told
		$this->setStory($story);

		// set a default page context
		$this->setPageContext(new PageContext);

		// create the actionlog
		$this->setActionLogger(new ActionLogger());

		// create an empty context
		$this->setCheckpoint(new StoryCheckpoint($this));

		// create our Prose Loader
		$this->setProseLoader();
	}

	// ==================================================================
	//
	// Getters and setters go here
	//
	// ------------------------------------------------------------------

	/**
	 * [description here]
	 *
	 * @return [type] [description]
	 */
	public function getActionLogger() {
	    return $this->actionLogger;
	}

	/**
	 * [Description]
	 *
	 * @param [type] $newactionLogger [description]
	 */
	public function setActionLogger(ActionLogger $actionLogger) {
	    $this->actionLogger = $actionLogger;

	    return $this;
	}

	/**
	 * [description here]
	 *
	 * @return [type] [description]
	 */
	public function getWebBrowser() {
	    return $this->webBrowser;
	}

	public function getRunningWebBrowser()
	{
		if (!is_object($this->webBrowser))
		{
			$this->startWebBrowser();
		}

		if (!is_object($this->webBrowser))
		{
			throw new E5xx_CannotStartWebBrowser();
		}

		return $this->webBrowser;
	}

	/**
	 * [Description]
	 *
	 * @param [type] $newbrowser [description]
	 */
	public function setWebBrowser($webBrowser) {
	    $this->webBrowser = $webBrowser;

	    return $this;
	}

	/**
	 * [description here]
	 *
	 * @return [type] [description]
	 */
	public function getCheckpoint() {
	    return $this->checkpoint;
	}

	/**
	 * [Description]
	 *
	 * @param [type] $newcheckpoint [description]
	 */
	public function setCheckpoint(StoryCheckpoint $checkpoint) {
	    $this->checkpoint = $checkpoint;

	    return $this;
	}

	/**
	 * [description here]
	 *
	 * @return [type] [description]
	 */
	public function getPageContext() {
	    return $this->pageContext;
	}

	/**
	 * [Description]
	 *
	 * @param [type] $newPageContext [description]
	 */
	public function setPageContext(PageContext $pageContext) {
	    $this->pageContext = $pageContext;

	    return $this;
	}

	/**
	 * [description here]
	 *
	 * @return [type] [description]
	 */
	public function getStory() {
	    return $this->story;
	}

	/**
	 * [Description]
	 *
	 * @param [type] $newstory [description]
	 */
	public function setStory($story) {
	    $this->story = $story;

	    return $this;
	}

	/**
	 * [description here]
	 *
	 * @return [type] [description]
	 */
	public function getStoryContext() {
	    return $this->storyContext;
	}

	/**
	 * [Description]
	 *
	 * @param [type] $newstoryContext [description]
	 */
	public function setStoryContext(StoryContext $storyContext) {
	    $this->storyContext = $storyContext;

	    // we need to update our ProseLoader, as the list of namespaces
	    // to search for Prose classes may have changed
	    $this->proseLoader->setNamespaces($this);

	    // all done
	    return $this;
	}

	/**
	 * [description here]
	 *
	 * @return [type] [description]
	 */
	public function getRuntimeConfigManager() {
	    return $this->runtimeConfigManager;
	}

	/**
	 * [Description]
	 *
	 * @param [type] $runtimeConfigManager [description]
	 */
	public function setRuntimeConfigManager($runtimeConfigManager) {
	    $this->runtimeConfigManager = $runtimeConfigManager;

	    return $this;
	}

	/**
	 * [description here]
	 *
	 * @return [type] [description]
	 */
	public function getWebProxy() {
	    return $this->webProxy;
	}

	/**
	 * [Description]
	 *
	 * @param [type] $newwebProxy [description]
	 */
	public function setWebProxy($webProxy) {
	    $this->webProxy = $webProxy;

	    return $this;
	}

	public function getCurrentPhase()
	{
		return $this->currentPhase;
	}

	public function setCurrentPhase($newPhase)
	{
		$this->currentPhase = $newPhase;
	}

	public function setProseLoader()
	{
		$this->proseLoader = new ProseLoader();
	}

	// ====================================================================
	//
	// Helpers to get parts of the story's context go here
	//
	// --------------------------------------------------------------------

	public function getAdminUser()
	{
		return $this->storyContext->env->adminUser;
	}

	public function getEnvironment()
	{
		return $this->storyContext->env;
	}

	public function getEnvironmentName()
	{
		return $this->storyContext->env->envName;
	}

	public function getRuntimeConfig()
	{
		return $this->storyContext->runtime;
	}

	public function saveRuntimeConfig()
	{
		if (!isset($this->runtimeConfigManager)) {
			throw new E5xx_ActionFailed(__METHOD__, "no runtimeConfigManager available");
		}

		$this->runtimeConfigManager->saveRuntimeConfig($this->storyContext->runtime);
	}

	public function getUser()
	{
		return $this->storyContext->user;
	}

	public function getUrl()
	{
		return $this->storyContext->env->url;
	}

	public function setUrl($url)
	{
		$this->storyContext->env->url = $url;

		return $this;
	}

	public function getDefines()
	{
		return $this->storyContext->defines;
	}

	public function getParams($mixed1 = array(), $mixed2 = array())
	{
		// our return value
		$return = array();

		// $mixed1 OR $mixed2 might be a StoryTemplate
		//
		// we've decided to support it either way to reduce the liklihood
		// of a mistake that causes a PHP error during testEnvironmentTeardown
		// phase
		foreach (array($mixed1, $mixed2) as $index => $mixed) {
			// $mixed1 might be a StoryTemplate
			if ($mixed instanceof StoryTemplate) {
				$return = $return + $mixed->getParams();
			}
			else if (is_array($mixed)) {
				$return = $return + $mixed;
			}
			else {
				// unsupported
				throw new \Exception("Unsupported param " . ($index + 1) . " to StoryTeller::getParams(); must be array or StoryTemplate object");
			}
		}

		// merge in any defines from the command-line
		$defines = $this->getDefines();
		foreach ($defines as $key => $value) {
			$return[$key] = $value;
		}

		// all done
		return $return;
	}

	// ==================================================================
	//
	// Accessors of other containers go here
	//
	// ------------------------------------------------------------------

	public function __call($methodName, $methodArgs)
	{
		// what class do we want?
		$className = $this->proseLoader->determineProseClassFor($methodName);

		// use the Prose Loader to create the object to call
		$obj = $this->proseLoader->loadProse($this, $className, $methodArgs);

		// did we find something?
		if (!is_object($obj)) {
			// alas, no
			throw new E5xx_NoMatchingActions($methodName);
		}

		// all done
		return $obj;
	}

	// ==================================================================
	//
	// Logging support
	//
	// ------------------------------------------------------------------

	public function startAction($text)
	{
		return $this->actionLogger->startAction($this->getUser(), $text);
	}

	public function closeAllOpenActions()
	{
		return $this->actionLogger->closeAllOpenActions();
	}

	// ==================================================================
	//
	// Starting and stopping browsers goes here
	//
	// ------------------------------------------------------------------

	public function startWebBrowser()
	{
		$httpProxy = new BrowserMobProxyClient();
		$httpProxy->enableFeature('paramLogs');

		$proxySession = $httpProxy->createProxy();

		$env = $this->getEnvironment();
		if (isset($env->username)) {
			$address = new HttpAddress($env->url);
			$proxySession->setHttpBasicAuth($address->hostname, $env->username, $env->password);
		}

		// start recording
		$proxySession->startHAR();

		// create the browser session
		$webDriver = new WebDriverClient();
		$browserSession = $webDriver->newSession(
			'chrome',
			array(
				'proxy' => $proxySession->getWebDriverProxyConfig()
			)
		);

		// remember what we've done!
		$this->setWebProxy($proxySession);
		$this->setWebBrowser($browserSession);

		// all done
	}

	public function stopWebBrowser()
	{
		// get the browser
		$browser = $this->getWebBrowser();

		// stop the web browser
		if (is_object($browser))
		{
			$browser->close();
			$this->setWebBrowser(null);
		}

		// get the proxy
		$proxy = $this->getWebProxy();

		// stop the proxy too
		if (is_object($proxy))
		{
			try {
				$proxy->close();
			}
			catch (Exception $e) {
				// do nothing - we don't care!
			}
			$this->setWebProxy(null);
		}
	}
}