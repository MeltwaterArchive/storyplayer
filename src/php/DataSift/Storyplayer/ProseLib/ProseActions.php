<?php

namespace DataSift\Storyplayer\ProseLib;

use DataSift\Stone\ExceptionsLib\E5xx_NotImplemented;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

class ProseActions
{
	protected $st = null;
	protected $args = array();
	protected $topElement = null;
	protected $topXpath   = null;

	public function __construct(StoryTeller $st, $args = array())
	{
		// save the StoryTeller object; we're going to need it!
		$this->st = $st;

		// save any arguments that have been passed into the constructor
		// our child classes may be interested in them
		if (!is_array($args)) {
			throw new E5xx_ActionFailed(__METHOD__);
		}
		$this->args = $args;

		// setup the page context
		$this->initPageContext();

		// run any context-specific setup that we need
		$this->initActions();
	}

	protected function initPageContext()
	{
		// shorthand
		$st = $this->st;

		// make sure we are looking at the right part of the page
		$pageContext = $st->getPageContext();
		$pageContext->switchToContext($st);
	}

	/**
	 * override this method if required (for example, for web browsers)
	 *
	 * @todo this needs moving into traits longer term
	 *
	 * @return void
	 */
	protected function initActions()
	{
		// do we have a web browser?
		$browser = $this->st->getWebBrowser();
		if (is_object($browser)) {
			// set our top XPATH node
			$this->setTopXpath("//html");

			// set our top element
			$topElement = $browser->element('xpath', '/html');
			$this->setTopElement($topElement);
		}
	}

	public function __call($methodName, $params)
	{
		// this only gets called if there's no matching method
		throw new E5xx_NotImplemented(get_class($this) . '::' . $methodName);
	}

	public function getTopElement()
	{
		return $this->topElement;
	}

	public function setTopElement($element)
	{
		$this->topElement = $element;
	}

	protected function getTopXpath()
	{
		return $this->topXpath;
	}

	protected function setTopXpath($xpath)
	{
		$this->topXpath = $xpath;
	}

	// ====================================================================
	//
	// Convertors go here
	//
	// --------------------------------------------------------------------

	public function toNum($string)
	{
		$final = str_replace(array(',', '$', ' '), '', $string);

		return (double)$final;
	}
}