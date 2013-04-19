<?php

namespace DataSift\Storyplayer\Prose;

use Exception;

use DataSift\Storyplayer\ApiLib\RestApiCall;
use DataSift\Storyplayer\ProseLib\ProseActions;
use DataSift\Storyplayer\ProseLib\E5xx_ActionFailed;

use DataSift\Stone\ExceptionsLib\E5xx_NotImplemented;

class CompilerActions extends ProseActions
{
	public function compileNullDataStream()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("compile stream that consumes no data");

		// compile it
		$result = $this->compile('interaction.sample > 100');

		// all done
		$log->endAction("CSDL hash is '{$result[0]}'");
		return $result;
	}

	public function compileMetronomeDataStream()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("compile stream that matches all metronome interactions");

		// compile it
		$result = $this->compile('interaction.type == "metronome"');

		// all done
		$log->endAction("CSDL hash is '{$result[0]}'");
		return $result;
	}

	public function compileSmallDataStream()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("compile stream that matches a small amount of data");

		// compile it
		$result = $this->compile('interaction.sample < 2');

		// all done
		$log->endAction("CSDL hash is '{$result[0]}'");
		return $result;
	}

	public function compileMediumDataStream()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("compile stream that matches a medium amount of data");

		// compile it
		$result = $this->compile('interaction.sample < 11');

		// all done
		$log->endAction("CSDL hash is '{$result[0]}'");
		return $result;
	}

	public function compileLargeDataStream()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("compile stream that matches a large amount of data");

		// compile it
		$result = $this->compile('interaction.sample < 20');

		// all done
		$log->endAction("CSDL hash is '{$result[0]}'");
		return $result;
	}

	public function compileLargerDataStream()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("compile stream that matches a larger still amount of data");

		// compile it
		$result = $this->compile('interaction.sample < 25');

		// all done
		$log->endAction("CSDL hash is '{$result[0]}'");
		return $result;
	}

	public function compileSillyDataStream()
	{
		// shorthand
		$st = $this->st;

		// what are we doing?
		$log = $st->startAction("compile stream that matches a silly amount of data");

		// compile it
		$result = $this->compile('interaction.sample < 50');

		// all done
		$log->endAction("CSDL hash is '{$result[0]}'");
		return $result;
	}

	public function compile($csdl)
	{
		// shorthand
		$st   = $this->st;
		$user = $st->getUser();
		$env  = $st->getEnvironment();

		// what are we doing?
		$log = $st->startAction("[ compile CSDL via the API ]");

		// get the API key
		$apikey = $st->fromApi()->getApiKey();

		// prepare the CSDL
		$csdl = urlencode($csdl);

		// make the call
		$result = $log->addStep("[ call the /compile REST API endpoint ]", function() use($st, $user, $apikey, $env, $csdl){
			$apiCall = new RestApiCall($user->username, $apikey);
			return $apiCall->get($env->restUrl . '/compile?csdl=' . $csdl);
		});

		// what happened?
		if (isset($result->hash) && isset($result->dpu)) {
			// success :)
			$log->endAction();
			return array($result->hash, $result->dpu);
		}

		// if we get here, something went wrong
		var_dump($result);
		throw new E5xx_ActionFailed(__METHOD__);
	}
}