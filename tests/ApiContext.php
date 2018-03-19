<?php
namespace Blockfolio\Test;


use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Blockfolio\API;

class ApiContext implements Context {



	public $sdk = false;
	public $lastResponse = false;

	/**
	 * ApiContext constructor.
	 *
	 * @param bool $sdk
	 */
	public function __construct() {
		$this->sdk = new API();
	}

	/**
	 * @When /^I call the API "([^"]*)" endpoint$/
	 */
	public function iCallTheAPIEndpoint($endpoint) {
		$this->lastResponse = $this->sdk->$endpoint();
	}

	/**
	 * @Then /^The response should contain "([^"]*)"$/
	 */
	public function theResponseShouldContain($key) {
		echo 'Checking for key "' . $key . '" within response.' . "\n";
		$exists = isset($this->lastResponse->$key);
		if (!$exists) {
			throw new \Exception('Key ' . $key . ' was not in response.');
		}
		if (is_string($this->lastResponse->$key)) {
			echo 'Found key "' . $key . '" with value "' . $this->lastResponse->$key . '"';
		} else {
			echo 'Found key "' . $key . '"';
		}
	}

	/**
	 * @When /^I call the "([^"]*)" endpoint with ticker "([^"]*)"$/
	 */
	public function iCallTheEndpointWithTicker($function, $ticker) {
		$this->lastResponse = $this->sdk->$function($ticker);
	}

	/**
	 * @When /^I call the "([^"]*)" endpoint with exchange "([^"]*)" and ticker "([^"]*)"$/
	 */
	public function iCallTheEndpointWithExchangeAndTicker($function, $exchange, $ticker) {
		$this->lastResponse = $this->sdk->$function($exchange, $ticker);
	}

	/**
	 * @When /^I call the "([^"]*)" endpoint with exchange "([^"]*)", ticker "([^"]*)" and duration "([^"]*)"$/
	 */
	public function iCallTheEndpointWithExchangeTickerAndDuration($function, $exchange, $ticker, $duration) {
		$this->lastResponse = $this->sdk->$function($exchange, $ticker, $duration);
	}
}
