<?php

namespace DataSift\Storyplayer\PlayerLib;

use Exception;

use DataSift\Storyplayer\ProseLib\DynamicContainer;
use DataSift\Storyplayer\ProseLib\PageContext;
use DataSift\Storyplayer\StoryLib\Story;

use DataSift\Stone\HttpLib\HttpAddress;
use DataSift\Stone\Log\LogLib;

use DataSift\BrowserMobProxy\BrowserMobProxy;
use DataSift\WebDriver\WebDriver;

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

	private $containerTypes = array (
		'using'     => 'Actions',
		'from'      => 'Determine',
		'calculate' => 'Calculator',
		'asserts'   => 'Asserts',
	);

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

	public function getRuntimeConfig()
	{
		return $this->storyContext->runtime;
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

	// ==================================================================
	//
	// Accessors of other containers go here
	//
	// ------------------------------------------------------------------

	public function __call($methodName, $methodArgs)
	{
		// break up the method name into separate words
		$words = $this->convertMethodNameToWords($methodName);

		// the first word determines what kind of dynamic container we want
		$containerType = array_shift($words);

		// is it actually an alias?
		if (isset($this->containerTypes[$containerType])) {
			// yes
			$containerType = $this->containerTypes[$containerType];
		}
		else {
			// not an alias that we know ... let it through
			$containerType = ucfirst($containerType);
		}

		// the remaining words determine the specific class to instantiate
		$className = '';
		foreach ($words as $word) {
			$className .= ucfirst($word);
		}

		// create the container
		// var_dump($containerType, $methodName);
		$container = new DynamicContainer($containerType, $this);

		// use the container to create the object to call
		$obj = call_user_func_array(array($container, $className), $methodArgs);

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

	/**
	 * this needs moving into its own trait perhaps, or into a static
	 * inside the Stone library
	 *
	 * @param  [type] $methodName [description]
	 * @return [type]             [description]
	 */
	protected function convertMethodNameToWords($methodName)
	{
		// turn the method name into an array of words
		$words = explode(' ', strtolower(preg_replace('/([^A-Z])([A-Z])/', "$1 $2", $methodName)));

		// all done
		return $words;
	}

	// ==================================================================
	//
	// Starting and stopping browsers goes here
	//
	// ------------------------------------------------------------------

	public function startWebBrowser()
	{
		$httpProxy = new BrowserMobProxy();
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
		$webDriver = new WebDriver();
		$browserSession = $webDriver->session(
			'chrome',
			array('proxy' => $proxySession->getWebDriverProxyConfig())
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