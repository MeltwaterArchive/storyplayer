<?php

namespace DataSift\Storyplayer\PlayerLib;

use DataSift\Stone\DataLib\DataPrinter;

class StoryCheckpoint
{
	/**
	 * the StoryTeller object
	 *
	 * @var DataSift\Storyplayer\PlayerLib\StoryTeller
	 */
	private $st;

	/**
	 * keep track of whether the checkpoint is readonly (true) or
	 * read-write(false)
	 *
	 * @var boolean
	 */
	private $readOnly = false;

	/**
	 * the data stored inside the checkpoint
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * our constructor
	 *
	 * @param StoryTeller $st
	 *        The StoryTeller object (which we will cache)
	 */
	public function __construct(StoryTeller $st)
	{
		// remember the StoryTeller object for future use
		$this->st = $st;
	}

	/**
	 * is the checkpoint currently readonly?
	 *
	 * @return boolean
	 *         TRUE if the checkpoint is currently readonly
	 *         FALSE if you can change the data in the checkpoint
	 */
	public function getReadOnly()
	{
		return $this->readOnly;
	}

	/**
	 * put the checkpoint into readonly mode
	 */
	public function setReadOnly()
	{
		$this->readOnly = true;
	}

	/**
	 * put the checkpoint into read-write mode
	 */
	public function setReadWrite()
	{
		$this->readOnly = false;
	}

	/**
	 * magic method to retrieve data from the checkpoint
	 *
	 * throws the E5xx_NoSuchDataInCheckpoint exception if you attempt
	 * to get data that does not exist
	 *
	 * @param  string $key
	 *         the name of the data to store
	 * @return mixed
	 *         the data stored in the checkpoint
	 */
	public function &__get($key)
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("retrieve '{$key}' from the checkpoint");

		// do we have the data to return?
		if (!isset($this->data[$key])) {
			// no, we do not
			$log->endAction("'{$key}' is not in the checkpoint");
			throw new E5xx_NoSuchDataInCheckpoint($key);
		}

		// yes, we do
		//
		// make a printable version of the data, for our logs
		$printer = new DataPrinter();
		$logValue = $printer->convertToString($this->data[$key]);

		$log->endAction("value is '{$logValue}'");

		// all done
		return $this->data[$key];
	}

	/**
	 * magic method to tell if the data is stored in the checkpoint or not
	 *
	 * @param  string  $key
	 *         the name of the data to test for
	 * @return boolean
	 *         TRUE if the data exists in the checkpoint
	 *         FALSE if the data does not exist in the checkpoint
	 */
	public function __isset($key)
	{
		return (isset($this->data[$key]));
	}

	/**
	 * magic method to store data in the checkpoint
	 *
	 * throws the E5xx_CheckpointIsReadOnly exception if you attempt
	 * to store data when the checkpoint is in readonly mode
	 *
	 * @param  string  $key
	 *         the name of the data to store
	 * @param  mixed   $value
	 *         the value to store in the checkpoint
	 * @return void
	 */
	public function __set($key, $value)
	{
		// shorthand
		$st = $this->st;

		// convert the value into something we can log
		$printer = new DataPrinter();
		$logValue = $printer->convertToString($value);

		// what are we doing?
		$log = $st->startAction("store '{$key}' => '{$logValue}' in the checkpoint");

		// are we allowed to change the data at this time?
		if ($this->readOnly)
		{
			// no, we are not
			$log->endAction("checkpoint is readonly; did not store '{$key}'");
			throw new E5xx_CheckpointIsReadOnly();
		}

		// if we get here, we're allowed to change the checkpoint
		$this->data[$key] = $value;

		// all done
		$log->endAction();
	}

    // ====================================================================
    //
    // Helpers
    //
    // These are mostly syntactic sugar, but they help a tiny bit with
    // code robustness because they can deal correctly with unset properties,
    // something many developers forget to check for
    //
    // Copied from BaseObject (rather than implemented as a trait) as
    // we want to support PHP 5.3 for now.  We will turn them into traits
    // when PHP 5.5 becomes the minimum requirement
    //
    // --------------------------------------------------------------------

    /**
     * Do we have a named property set to non-null?
     *
     * @param  string $propName
     * @return boolean
     */
    public function has($propName)
    {
        return (isset($this->$propName));
    }

    /**
     * Is the named property set to true?
     *
     * @param  string $propName
     * @return boolean
     */
    public function is($propName)
    {
        return (isset($this->$propName) && $this->$propName);
    }

    /**
     * is the named property set to false?
     *
     * @param  string $propName
     * @return boolean
     */
    public function isNot($propName)
    {
        return (!isset($this->$propName) || $this->$propName != true);
    }

    /**
     * Do we have a named property, and is it a non-empty array?
     *
     * @param  string $propName
     * @return boolean
     */
    public function hasList($propName)
    {
        return (isset($this->$propName)
            && ((is_array($this->$propName) && count($this->$propName) > 0) ||
               (is_object($this->$propName))));
    }

    /**
     * retrieve the named property as an associative array, even if it is
     * actually an object
     *
     * @param  string $propName
     * @return array
     */
    public function getList($propName)
    {
        // do we have the property at all?
        if (!isset($this->$propName))
        {
            // no ... send back an empty list
            return array();
        }

        // is the property already a list?
        if (is_array($this->$propName))
        {
            // yes ... no conversion needed
            return $this->$propName;
        }

        // is the property something we can convert?
        if (is_object($this->$propName))
        {
            // yes
            $return = array();
            foreach ($this->$propName as $key => $value)
            {
                $return[$key] = $value;
            }

            return $return;
        }

        // if we get here, the property isn't naturally a list
        return array();
    }

    /**
     * return the named property as a string, or return the default if
     * the property isn't a string
     *
     * @param  string $propName name of property to retrieve
     * @param  string $default  default value to return if property not set
     * @return string
     */
    public function getString($propName, $default = '')
    {
        // does this property exist at all?
        if (!isset($this->$propName))
        {
            // no, so return the default
            return $default;
        }

        // is this property something that can be auto-converted to a
        // string reliably?
        if (is_string($this->$propName) || is_int($this->$propName) || is_double($this->$propName))
        {
            // yes
            return (string)$this->$propName;
        }

        // starting to clutch at straws now

        // a boolean, perhaps?
        if (is_bool(($this->$propName)))
        {
            if ($this->$propName)
            {
                return 'TRUE';
            }

            return 'FALSE';
        }

        // is it an object that can convert itself to a string?
        if (is_object($this->$propName))
        {
            $refObj = new ReflectionObject($this->$propName);
            if ($refObj->hasMethod('__toString'))
            {
                return (string)$this->$propName;
            }

            // sadly, the object cannot convert itself to a string
            return $default;
        }

        // add any other conversions in here

        // okay, we give up
        return $default;
    }

    /**
     * convert our public properties to an array
     *
     * @return array
     */
    public function getProperties_asList($prefix = null)
    {
        $return = array();

        // get a list of the properties of the $params object
        $refObj   = new ReflectionObject($this);
        $refProps = $refObj->getProperties(ReflectionProperty::IS_PUBLIC);

        // convert each property into an array entry
        foreach ($refProps as $refProp)
        {
            $propKey      = $refProp->getName();
            $retKey       = $propKey;

            // do we need to enforce the prefix?
            if ($prefix !== null && substr($this->$propKey, 0, strlen($prefix)) !== $prefix)
            {
                // yes we do
                $retKey = $prefix . $propKey;
            }

            // set the value
            $return[$retKey] = $this->$propKey;
        }

        // return the array that we've built
        return $return;
    }
}