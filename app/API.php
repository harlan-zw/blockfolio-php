<?php

namespace Blockfolio;

use Blockfolio\Exception\MissingAPIKeyException;
use Blockfolio\Exception\MissingMagicException;
use Blockfolio\Exception\UnsuccessfulResponse;

class API {

	/**
	 * Environment and option value name for the key we need to pass in
	 */
	public const API_KEY_NAME = 'BLOCKFOLIO_API_KEY';
	public const API_MAGIC_KEY_NAME = 'BLOCKFOLIO_MAGIC';

	/**
	 * Default options sent to endpoints
	 */
	private const DEFAULTS_OPTIONS = [
		'fiat_currency' => 'AUD',
		'locale'        => 'en-US',
		'debug'         => false, // turn to true if you want curl output
		'use_alias'     => 'true',
	];

	private $client;
	private $key;
	private $options;

	/**
	 * Create an instance of the blockfolio API
	 *
	 * The options available are passed in the relevant endpoints to blockfolio.
	 * - fiat_currency Which currency to display fiat in. Default is USD
	 * - locale Which language text strings should be displayed in. Default is en-US
	 * - use_alias Not sure what this is for but blockfolio wants it
	 *
	 */
	public function __construct(array $options = []) {

		$options = array_merge($options, self::DEFAULTS_OPTIONS);

		// try and pull the api key from env if it has not been provided
		if (! isset($options[ self::API_KEY_NAME ])) {
			$options[ self::API_KEY_NAME ] = getenv(self::API_KEY_NAME);
		}

		// try and pull the api key from env if it has not been provided
		if (! isset($options[ self::API_MAGIC_KEY_NAME ])) {
			$options[ self::API_MAGIC_KEY_NAME ] = getenv(self::API_MAGIC_KEY_NAME);
		}

		if (empty($options[self::API_MAGIC_KEY_NAME])) {
			throw new MissingMagicException('You must provide your magic for blockfolio to work.');
		}

		$this->options['magic'] = $options[self::API_MAGIC_KEY_NAME];
		$this->key     = $options[ self::API_KEY_NAME ];
		$this->options = $options;
		$this->client  = new BlockfolioClient($options);
	}

	/**
	 * Find the API version
	 * @return \stdClass
	 */
	public function version() {
		return $this->get(__FUNCTION__, [ 'platform' => 'php-sdk' ]);
	}

	private function get($endpoint, $params = []) {
		$response = $this->client->get($endpoint, [ 'query' => $params ]);
		$body     = json_decode($response->getBody()->getContents());
		if ($body->success !== true) {
			throw new UnsuccessfulResponse(print_r($body, true));
		}
		return $body;
	}

	/**
	 * Find the API system status
	 * @return \stdClass
	 */
	public function system_status() {
		return $this->get(__FUNCTION__);
	}

	/**
	 * Gets a list of all coins available
	 * @return \stdClass
	 */
	public function coinlist_v6() {
		return $this->get(__FUNCTION__);
	}

	/**
	 * Get a list of currencies
	 * @return \stdClass
	 */
	public function currency() {
		return $this->get(__FUNCTION__);
	}

	/**
	 * Gets all of your positions for a ticker
	 *
	 * @param string $ticker The coins ticker ID. For example ETH-VEN
	 *
	 * @return \stdClass
	 */
	public function get_positions_v2(string $ticker) {
		$options = [
			'fiat_currency' => $this->options['fiat_currency'],
			'locale' => $this->options['locale'],
			'use_alias' => $this->options['use_alias']
		];
		return $this->get($this->appendKey(__FUNCTION__) . '/' . $ticker, $options);
	}

	private function appendKey($endpoint) {
		// if still no key is set we throw an error
		if (! isset($this->key)) {
			throw new MissingAPIKeyException();
		}
		return $endpoint . '/' . $this->key;
	}

	/**
	 * Gets all of your combined positions for a ticker
	 *
	 * @param string $ticker The coins ticker ID. For example ETH-VEN Get all data points for a ticker on an exchange.  _Binance used by default_
	 *
	 * @return \stdClass
	 */
	public function get_combined_position(string $ticker) {
		$options = [
			'fiat_currency' => $this->options['fiat_currency'],
			'locale' => $this->options['locale'],
			'use_alias' => $this->options['use_alias']
		];
		return $this->get($this->appendKey(__FUNCTION__) . '/' . $ticker, $options);
	}

	/**
	 * Gets the details about an exchanges ticker
	 *
	 * @param string $exchange The exchange name. For example binance
	 * @param string $ticker The coins ticker ID. For example ETH-VEN
	 *
	 * @return \stdClass
	 */
	public function marketdetails_v2(string $exchange, string $ticker) {
		$options = [
			'fiat_currency' => $this->options['fiat_currency'],
			'locale' => $this->options['locale'],
			'use_alias' => $this->options['use_alias']
		];
		return $this->get($this->appendKey(__FUNCTION__) . '/' . $exchange . '/' . $ticker, $options);
	}

	/**
	 * Gets all of your positions
	 *
	 * @return \stdClass
	 */
	public function get_all_positions() {
		$options = [
			'fiat_currency' => $this->options['fiat_currency'],
			'locale' => $this->options['locale'],
			'use_alias' => $this->options['use_alias']
		];
		return $this->get($this->appendKey(__FUNCTION__), $options);
	}

	/**
	 * Gets an exchanges candlesticks for a ticker for a duration. This is usually just binance.
	 *
	 * @param string $exchange The exchange name. For example binance
	 * @param string $ticker The coins ticker ID. For example ETH-VEN
	 * @param string $duration The duration for the candlesticks, such as 'year'
	 *
	 * @return \stdClass
	 */
	public function candlestick(string $exchange, string $ticker, string $duration) {
		$options = [
			'fiat_currency' => $this->options['fiat_currency'],
			'locale' => $this->options['locale'],
			'use_alias' => $this->options['use_alias']
		];
		return $this->get($this->appendKey(__FUNCTION__) . '/' . $exchange . '/' . $ticker . '/' . $duration, $options);
	}

	/**
	 * Get all data points for a ticker on an exchange.  Binance used by default
	 *
	 * @param string $exchange The exchange name. For example binance
	 * @param string $ticker The coins ticker ID. For example ETH-VEN
	 *
	 * @return \stdClass
	 */
	public function orderbook(string $exchange, string $ticker) {
		$options = [
			'fiat_currency' => $this->options['fiat_currency'],
			'locale' => $this->options['locale'],
			'use_alias' => $this->options['use_alias']
		];
		return $this->get(__FUNCTION__ . '/' . $exchange . '/' . $ticker, $options);
	}

	/**
	 * Change the key at runtime. Useful if we need to get different users data.
	 *
	 * @param $key
	 */
	public function setKey(string $key) {
		$this->key = $key;
	}
}
