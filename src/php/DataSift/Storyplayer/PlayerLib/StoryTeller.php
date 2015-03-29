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

use DataSift\Storyplayer\Injectables;
use DataSift\Storyplayer\Cli\RuntimeConfigManager;
use DataSift\Storyplayer\CommandLib\CommandRunner;
use DataSift\Storyplayer\Output;
use DataSift\Storyplayer\Phases\Phase;
use DataSift\Storyplayer\DeviceLib;
use DataSift\Storyplayer\OutputLib\CodeFormatter;
use Prose\E4xx_ObsoleteProse;
use Prose\E5xx_NoMatchingActions;
use Prose\PageContext;

use DataSift\Stone\ObjectLib\BaseObject;

/**
 * our main facilitation class
 *
 * all actions and tests inside a story are executed through an instance
 * of this class, making this class the StoryTeller :)
 *
 * @method Prose\AssertsArray assertsArray(array $expected)
 * @method Prose\AssertsBoolean assertsBoolean(boolean $expected)
 * @method Prose\AssertsDouble assertsDouble(float $expected)
 * @method Prose\AssertsInteger assertsInteger(integer $expected)
 * @method Prose\AssertsObject assertsObject(object $expected)
 * @method Prose\AssertsString assertsString(string $expected)
 * @method Prose\ExpectsBrowser expectsBrowser()
 * @method Prose\ExpectsEc2Image expectsEc2Image(string $amiId)
 * @method Prose\ExpectsFailure expectsFailure()
 * @method Prose\ExpectsForm expectsForm(string $formId)
 * @method Prose\ExpectsGraphite expectsGraphite()
 * @method Prose\ExpectsHost expectsHost($hostDetails)
 * @method Prose\ExpectsHostsTable expectsHostsTable()
 * @method Prose\ExpectsHttpResponse expectsHttpResponse(HttpClientResponse $response)
 * @method Prose\ExpectsIframe expectsIframe(string $id)
 * @method Prose\ExpectsProcessesTable expectsProcessesTable()
 * @method Prose\ExpectsRuntimeTable expectsRuntimeTable(string $tableName)
 * @method Prose\ExpectsShell expectsShell()
 * @method Prose\ExpectsUuid expectsUuid()
 * @method Prose\ExpectsZmq expectsZmq()
 * @method Prose\FromAws fromAws()
 * @method Prose\FromBrowser fromBrowser()
 * @method Prose\FromCheckpoint fromCheckpoint()
 * @method Prose\FromConfig fromConfig()
 * @method Prose\FromCurl fromCurl()
 * @method Prose\FromEc2 fromEc2()
 * @method Prose\FromEc2Instance fromEc2Instance(string $hostname)
 * @method Prose\FromEnvironment fromEnvironment()
 * @method Prose\FromFacebook fromFacebook()
 * @method Prose\FromFile fromFile()
 * @method Prose\FromForm fromForm(string $formId)
 * @method Prose\FromGraphite fromGraphite()
 * @method Prose\FromHost fromHost(string $hostId)
 * @method Prose\FromHostsTable fromHostsTable()
 * @method Prose\FromHttp fromHttp()
 * @method Prose\FromIframe fromIframe(string $id)
 * @method Prose\FromProcessesTable fromProcessesTable()
 * @method Prose\FromRolesTable fromRolesTable()
 * @method Prose\FromRuntimeTable fromRuntimeTable(string $tableName)
 * @method Prose\FromRuntimeTableForTargetEnvironment fromRuntimeTableForTargetEnvironment()
 * @method Prose\FromSauceLabs fromSauceLabs()
 * @method Prose\FromShell fromShell()
 * @method Prose\FromStoryplayer fromStoryplayer()
 * @method Prose\FromSupervisor fromSupervisor()
 * @method Prose\FromTargetsTable fromTargetsTable()
 * @method Prose\FromTestEnvironment fromTestEnvironment()
 * @method Prose\FromUuid fromUuid()
 * @method Prose\UsingBrowser usingBrowser()
 * @method Prose\UsingCheckpoint usingCheckpoint()
 * @method Prose\UsingDoppeld usingDoppeld()
 * @method Prose\UsingEc2 usingEc2()
 * @method Prose\UsingEc2Instance usingEc2Instance(string $hostname)
 * @method Prose\UsingFacebookGraphApi usingFacebookGraphApi()
 * @method Prose\UsingFile usingFile()
 * @method Prose\UsingForm usingForm(string $formId)
 * @method Prose\UsingHornet usingHornet()
 * @method Prose\UsingHost usingHost(string $hostId)
 * @method Prose\UsingHostsTable usingHostsTable()
 * @method Prose\UsingHttp usingHttp()
 * @method Prose\UsingIframe usingIframe(string $id)
 * @method Prose\UsingLog usingLog()
 * @method Prose\UsingProcessesTable usingProcessesTable()
 * @method Prose\UsingProvisioning usingProvisioning()
 * @method Prose\UsingProvisioningDefinition usingProvisioningDefinition(ProvisioningDefinition $definition)
 * @method Prose\UsingProvisioningEngine usingProvisioningEngine(string $engine)
 * @method Prose\UsingReporting usingReporting()
 * @method Prose\UsingRolesTable usingRolesTable()
 * @method Prose\UsingRuntimeTable usingRuntimeTable(string $tableName)
 * @method Prose\UsingRuntimeTableForTargetEnvironment usingRuntimeTableForTargetEnvironment()
 * @method Prose\UsingSauceLabs usingSauceLabs()
 * @method Prose\UsingSavageD usingSavageD()
 * @method Prose\UsingShell usingShell()
 * @method Prose\UsingTargetsTable usingTargetsTable()
 * @method Prose\UsingTimer usingTimer()
 * @method Prose\UsingVagrant usingVagrant()
 * @method Prose\UsingYamlFile usingYamlFile(string $filename)
 * @method Prose\UsingZmq usingZmq()
 * @method Prose\UsingZookeeper usingZookeeper(string $hostname)
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
     * singleton instance of StoryTeller
     * @var StoryTeller
     */
    static protected $self = null;

    /**
     * the story that is being played
     * @var Story
     */
    private $story = null;

    private $pageContext = null;
    private $checkpoint = null;

    /**
     * the script that is being played
     */
    private $scriptFilename = null;

    /**
     *
     * @var PhaseLoader
     */
    private $phaseLoader = null;

    /**
     *
     * @var Prose_Loader
     */
    private $proseLoader = null;

    // support for the current runtime config
    private $runtimeConfig = null;
    private $runtimeConfigManager = null;

    // our output
    private $output = null;

    /**
     *
     * @var \Datasift\Storyplayer\PlayerLib\Action_Logger
     */
    private $actionLogger;

    /**
     * which of the steps is currently being executed?
     * @var \DataSift\Storyplayer\Phases\Phase
     */
    private $currentPhase = null;

    // test device support
    private $deviceDetails = null;
    private $deviceName = null;
    private $deviceAdapter = null;
    private $persistDevice = false;

    // the config that Storyplayer is running with
    private $config = null;

    // the environment we are testing
    private $persistTestEnvironment = false;

    // story / template params
    private $defines = [];

    // our repository of parsed code, for printing code statements
    private $codeParser = null;
    private $lastSeenCodeLine = null;

    // our data formatter
    private $dataFormatter = null;

    // process support
    private $persistProcesses = false;

    public function __construct(Injectables $injectables)
    {
        // remember our output object
        $this->setOutput($injectables->output);

        // our code parser
        $this->setCodeParser($injectables->codeParser);

        // our data formatter
        $this->setDataFormatter($injectables->dataFormatter);

        // set a default page context
        $this->setPageContext(new PageContext);

        // create the actionlog
        $this->setActionLogger(new Action_Logger($injectables));

        // create an empty checkpoint
        $this->setCheckpoint(new Story_Checkpoint($this));

        // create our Prose Loader
        $this->setProseLoader($injectables->proseLoader);

        // create our Phase Loader
        $this->setPhaseLoader($injectables->phaseLoader);

        // remember the device we are testing with
        $this->setDevice($injectables->activeDeviceName, $injectables->activeDevice);

        // the config that we have loaded
        $this->setConfig($injectables->activeConfig);

        // our runtime config
        $this->setRuntimeConfig($injectables->getRuntimeConfig());
        $this->setRuntimeConfigManager($injectables->getRuntimeConfigManager());

        self::$self = $this;
    }

    public static function instance()
    {
        return self::$self;
    }

    // ==================================================================
    //
    // Getters and setters go here
    //
    // ------------------------------------------------------------------

    /**
     *
     *
     * @return Action_Logger
     */
    public function getActionLogger() {
        return $this->actionLogger;
    }

    /**
     *
     *
     * @param Action_Logger $actionLogger
     * @return StoryTeller
     */
    public function setActionLogger(Action_Logger $actionLogger) {
        $this->actionLogger = $actionLogger;

        return $this;
    }

    /**
     *
     *
     * @return Story_Checkpoint
     */
    public function getCheckpoint() {
        return $this->checkpoint;
    }

    /**
     *
     *
     * @param Story_Checkpoint $checkpoint
     * @return StoryTeller
     */
    public function setCheckpoint(Story_Checkpoint $checkpoint) {
        $this->checkpoint = $checkpoint;

        return $this;
    }

    /**
     *
     *
     * @return PageContext
     */
    public function getPageContext() {
        return $this->pageContext;
    }

    /**
     *
     *
     * @param PageContext $pageContext
     * @return StoryTeller
     */
    public function setPageContext(PageContext $pageContext) {
        $this->pageContext = $pageContext;

        return $this;
    }

    /**
     *
     *
     * @return Story
     */
    public function getStory()
    {
        return $this->story;
    }

    /**
     * track the story that we are testing
     *
     * NOTE: setting the story also creates a new Story_Result object
     *       so that we can track how the story is getting on
     *
     * @param Story $story
     * @return StoryTeller
     */
    public function setStory(Story $story)
    {
        // are we already tracking this story?
        if ($this->story == $story) {
            return $this;
        }

        // we're now tracking this story
        $this->story = $story;

        // all done
        return $this;
    }

    /**
     * @return string
     */
    public function getScriptFilename()
    {
        return $this->scriptFilename;
    }

    /**
     * @return void
     */
    public function setScriptFilename($filename)
    {
        $this->scriptFilename = $filename;
    }

    /**
     *
     *
     * @return RuntimeConfigManager
     */
    public function getRuntimeConfigManager() {
        return $this->runtimeConfigManager;
    }

    /**
     *
     *
     * @param RuntimeConfigManager $runtimeConfigManager
     * @return StoryTeller
     */
    public function setRuntimeConfigManager(RuntimeConfigManager $runtimeConfigManager) {
        $this->runtimeConfigManager = $runtimeConfigManager;

        return $this;
    }

    /**
     *
     * @return Phase
     */
    public function getCurrentPhase()
    {
        return $this->currentPhase;
    }

    /**
     *
     * @return string
     */
    public function getCurrentPhaseName()
    {
        return $this->currentPhase->getPhaseName();
    }

    /**
     *
     * @param Phase $newPhase
     * @return void
     */
    public function setCurrentPhase(Phase $newPhase)
    {
        $this->currentPhase = $newPhase;
    }

    /**
     * @return void
     */
    public function setProseLoader($proseLoader)
    {
        $this->proseLoader = $proseLoader;
    }

    /**
     *
     * @return PhaseLoader
     */
    public function getPhaseLoader()
    {
        return $this->phaseLoader;
    }

    public function setPhaseLoader($phaseLoader)
    {
        $this->phaseLoader = $phaseLoader;
    }

    /**
     *
     * @return Output
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     *
     * @param Output $output
     * @return void
     */
    public function setOutput(Output $output)
    {
        $this->output = $output;
    }

    public function getCodeParser()
    {
        return $this->codeParser;
    }

    public function setCodeParser($codeParser)
    {
        $this->codeParser = $codeParser;
    }

    public function setDataFormatter($dataFormatter)
    {
        $this->dataFormatter = $dataFormatter;
    }

    // ====================================================================
    //
    // Helpers to get parts of the story's context go here
    //
    // --------------------------------------------------------------------

    /**
     * @return object
     */
    public function getConfig()
    {
        // get our config
        $return = $this->config->getExpandedConfig();

        // all done
        return $return;
    }

    /**
     * @return \DataSift\Storyplayer\ConfigLib\ActiveConfig
     */
    public function getActiveConfig()
    {
        return $this->config;
    }

    /**
     * @return void
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return object
     */
    public function getRuntimeConfig()
    {
        return $this->runtimeConfig;
    }

    /**
     * @return void
     */
    public function setRuntimeConfig($runtimeConfig)
    {
        $this->runtimeConfig = $runtimeConfig;
    }

    /**
     * @return void
     */
    public function saveRuntimeConfig()
    {
        if (!isset($this->runtimeConfigManager)) {
            throw new E5xx_ActionFailed(__METHOD__, "no runtimeConfigManager available");
        }

        $this->runtimeConfigManager->saveRuntimeConfig($this->runtimeConfig, $this->output);
    }

    // ==================================================================
    //
    // System under test support
    //
    // ------------------------------------------------------------------

    public function getSystemUnderTestConfig()
    {
        return $this->config->getExpandedConfig('systemundertest');
    }

    // ==================================================================
    //
    // Test environment support
    //
    // ------------------------------------------------------------------

    /**
     * @return object
     */
    public function getTestEnvironmentConfig()
    {
        $retval = $this->config->getExpandedConfig();
        return $retval->target;
    }

    /**
     * @return string
     */
    public function getTestEnvironmentName()
    {
        $envConfig = $this->getTestEnvironmentConfig();
        return $envConfig->name;
    }

    /**
     * @return string
     */
    public function getTestEnvironmentSignature()
    {
        return md5(json_encode($this->getTestEnvironmentConfig()));
    }

    /**
     * @return bool
     */
    public function getPersistTestEnvironment()
    {
        return $this->persistTestEnvironment;
    }

    /**
     * @return void
     */
    public function setPersistTestEnvironment()
    {
        $this->persistTestEnvironment = true;
    }

    // ==================================================================
    //
    // Test user support
    //
    // ------------------------------------------------------------------

    /**
     * have we loaded test users off disk at all?
     *
     * @var boolean
     */
    private $hasTestUsers = false;

    /**
     * our list of test users
     *
     * @var null|\DataSift\Stone\ObjectLib\BaseObject
     */
    private $testUsers = null;

    /**
     * should we treat the file we loaded test users from as read-only?
     *
     * @var boolean
     */
    private $testUsersFileIsReadOnly = false;

    /**
     * which file did we load the test users from?
     *
     * @var string
     */
    private $testUsersFilename = null;

    /**
     * return the list of test users
     *
     * @return \DataSift\Stone\ObjectLib\BaseObject
     */
    public function getTestUsers()
    {
        // make sure we have an object to return
        if (!isset($this->testUsers)) {
            $this->testUsers = new BaseObject;
        }

        return $this->testUsers;
    }

    /**
     * have we loaded any test users from disk?
     *
     * @return boolean
     *         TRUE if we have
     */
    public function hasTestUsers()
    {
        return $this->hasTestUsers;
    }

    /**
     * remember the test users that we have
     *
     * @param \DataSift\Stone\ObjectLib\BaseObject $users
     *        our test users
     */
    public function setTestUsers($users)
    {
        $this->testUsers = $users;
        $this->hasTestUsers = true;
    }

    /**
     * retrieve the filename we loaded test users from
     *
     * @return string
     */
    public function getTestUsersFilename()
    {
        return $this->testUsersFilename;
    }

    /**
     * remember the filename we loaded test users from
     *
     * we'll re-use this filename later on when it is time to save
     * the test users back to disk
     *
     * @param string $filename
     *        the filename to remember
     */
    public function setTestUsersFilename($filename)
    {
        $this->testUsersFilename = $filename;
        $this->hasTestUsers = true;
    }

    /**
     * should we treat the file on disk where we loaded test user
     * data from as read-only?
     *
     * NOTE:
     *
     * we never treat the loaded data as read-only. Stories are
     * free to change this data, and these changes will persist
     * between stories. We just won't save any changes back to
     * disk if this method call returns TRUE.
     *
     * @return boolean
     */
    public function getTestUsersFileIsReadOnly()
    {
        return $this->testUsersFileIsReadOnly;
    }

    /**
     * set whether or not we treat the file on disk were we loaded
     * test user data from as read-only
     *
     * NOTE:
     *
     * we never treat the loaded data as read-only. Stories are
     * free to change this data, and these changes will persist
     * between stories. We just won't save any changes back to
     * disk if you set this to TRUE.
     *
     * @param boolean $readOnly
     *        TRUE if we should not save data back to this file
     */
    public function setTestUsersFileIsReadOnly($readOnly = true)
    {
        $this->testUsersFileIsReadOnly = $readOnly;
    }

    // ==================================================================
    //
    // Per-story parameter support
    //
    // ------------------------------------------------------------------

    /**
     * @return array
     */
    public function getParams()
    {
        // get the current parameters from the story
        //
        // NOTE that we deliberately don't cache $return in here, as
        // the parameters storied in the story can (in theory) change
        // at any moment
        //
        // NOTE that these are (deliberately) completely independent
        // from anything set using -D on the command-line
        //
        // parameters are now simply a way for stories to pass settings
        // into StoryTemplates that they are based upon, nothing more
        return $this->getStory()->getParams();
    }

    // ==================================================================
    //
    // Accessors of other containers go here
    //
    // ------------------------------------------------------------------

    /**
     *
     * @param  string $methodName
     * @param  array  $methodArgs
     * @return mixed
     */
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

        // who called us?
        $stackTrace = debug_backtrace();
        $codeLine = $this->codeParser->buildExecutingCodeLine($stackTrace);
        $this->lastSeenCodeLine = null;
        if ($codeLine && !empty($codeLine)) {
            $this->lastSeenCodeLine = $codeLine;
        }

        // all done
        return $obj;
    }

    // ==================================================================
    //
    // Logging support
    //
    // ------------------------------------------------------------------

    /**
     * @return Action_LogItem
     */
    public function startAction($text)
    {
        return $this->actionLogger->startAction($text, $this->lastSeenCodeLine);
    }

    /**
     * @return void
     */
    public function closeAllOpenActions()
    {
        return $this->actionLogger->closeAllOpenActions();
    }

    /**
     * @return void
     */
    public function convertDataForOutput($data)
    {
        return $this->dataFormatter->convertData($data);
    }

    // ==================================================================
    //
    // Device support
    //
    // ------------------------------------------------------------------

    /**
     * @return bool
     */
    public function getPersistDevice()
    {
        return $this->persistDevice;
    }

    /**
     * @return void
     */
    public function setPersistDevice()
    {
        $this->persistDevice = true;
    }

    /**
     * @return BaseObject
     */
    public function getDeviceDetails()
    {
        return $this->deviceDetails;
    }

    /**
     * @return \DataSift\Storyplayer\DeviceLib\DeviceAdapter
     */
    public function getDeviceAdapter()
    {
        if (!isset($this->deviceAdapter)) {
            return null;
        }

        return $this->deviceAdapter;
    }

    /**
     * @param DeviceLib\DeviceAdapter|null $adapter
     */
    public function setDeviceAdapter($adapter)
    {
        $this->deviceAdapter = $adapter;

        return $this;
    }

    /**
     * @return string
     */
    public function getDeviceName()
    {
        return $this->deviceName;
    }

    /**
     * @return \DataSift\WebDriver\WebDriverSession
     */
    public function getRunningDevice()
    {
        if (!is_object($this->deviceAdapter))
        {
            $this->startDevice();
        }

        if (!is_object($this->deviceAdapter))
        {
            throw new E5xx_CannotStartDevice();
        }

        return $this->deviceAdapter->getDevice();
    }

    /**
     * @param string $deviceName
     * @param BaseObject $deviceDetails
     */
    public function setDevice($deviceName, $deviceDetails)
    {
        $this->deviceName    = $deviceName;
        $this->deviceDetails = $deviceDetails;
    }

    /**
     * @return void
     */
    public function startDevice()
    {
        // what are we doing?
        $log = $this->startAction('start the test device');

        // what sort of browser are we starting?
        $deviceDetails = $this->getDeviceDetails();

        // get the adapter
        $adapter = DeviceLib::getDeviceAdapter($deviceDetails);

        // initialise the adapter
        $adapter->init($deviceDetails);

        // start the browser
        $adapter->start($this);

        // remember the adapter
        $this->setDeviceAdapter($adapter);

        // do we have a deviceSetup() phase?
        if (isset($this->story) && $this->story->hasDeviceSetup()) {
            // get the callbacks to call
            $callbacks = $this->story->getDeviceSetup();

            // make the call
            //
            // we do not need to wrap these in a TRY/CATCH block,
            // as we are already running inside one of the story's
            // phases
            foreach ($callbacks as $callback){
                call_user_func($callback, $this);
            }
        }

        // all done
        $log->endAction();
    }

    /**
     * @return void
     */
    public function stopDevice()
    {
        // get the browser adapter
        $adapter = $this->getDeviceAdapter();

        // stop the web browser
        if (!$adapter) {
            // nothing to do
            return;
        }

        // what are we doing?
        $log = $this->startAction('stop the test device');

        // do we have a deviceTeardown() phase?
        //
        // we need to run this BEFORE we stop the device, otherwise
        // the deviceTeardown() phase has no device to work with
        if (isset($this->story) && $this->story->hasDeviceTeardown()) {
            // get the callbacks to call
            $callbacks = $this->story->getDeviceTeardown();

            // make the call
            //
            // we do not need to wrap these in a TRY/CATCH block,
            // as we are already running inside one of the story's
            // phases
            foreach ($callbacks as $callback){
                call_user_func($callback, $this);
            }
        }

        // stop the browser
        $adapter->stop();

        // destroy the adapter
        $this->setDeviceAdapter(null);

        // all done
        $log->endAction();
    }

    // ==================================================================
    //
    // Processes support
    //
    // ------------------------------------------------------------------

    /**
     * @return bool
     */
    public function getPersistProcesses()
    {
        return $this->persistProcesses;
    }

    /**
     * @return void
     */
    public function setPersistProcesses()
    {
        $this->persistProcesses = true;
    }

    // ==================================================================
    //
    // Helpful methods that we can use to help with testing
    //
    // ------------------------------------------------------------------

    /**
     * @return CommandRunner
     */
    public function getNewCommandRunner()
    {
        return new CommandRunner();
    }

    // ==================================================================
    //
    // Features from v1 that we no longer support
    //
    // ------------------------------------------------------------------

    /**
     * @return \Prose\FromEnvironment
     */
    public function getEnvironment()
    {
        return new \Prose\FromEnvironment($this);
    }

    /**
     * @return void
     */
    public function getEnvironmentName()
    {
        throw new E4xx_ObsoleteProse(
            '$st->getEnvironmentName()',
            '$st->fromTestEnvironment()->getName()'
        );
    }
}
