<?php
namespace Blockfolio;

use Blockfolio\Exception\MissingAPIKeyException;
use Blockfolio\Exception\UnsuccessfulResponse;

class API {

	public const EXCHANGE_BINANCE = 'binance';
	public const EXCHANGE_KUCOIN = 'kucoin';

	public const API_KEY_NAME = 'BLOCKFOLIO_API_KEY';

	private const DEFAULTS_OPTIONS = [
		'fiat_currency' => 'USD',
		'locale' => 'en-US',
		'use_alias' => true,
	];

	private $client;
	private $key;
	private $options;

	/**
	 * SDK constructor.
	 */
	public function __construct(array $options = []) {

		$options = array_merge($options, self::DEFAULTS_OPTIONS);

		// try and pull the api key from env if it has not been provided
		if (!isset($options[self::API_KEY_NAME])) {
			$options[self::API_KEY_NAME] = getenv(self::API_KEY_NAME);
		}

		// if still no key is set we throw an error
		if (!isset($options[self::API_KEY_NAME])) {
			throw new MissingAPIKeyException();
		}

		$this->key = $options[self::API_KEY_NAME];
		$this->options = $options;
		$this->client = new BlockfolioClient($options);
	}

	/**
	 * Find the API version
	 */
	public function version() {
		return $this->get(__FUNCTION__, ['platform' => 'php-sdk']);
	}

	/**
	 * Find the API version
	 */
	public function system_status() {
		return $this->get(__FUNCTION__);
	}

	public function coinlist_v6() {
		return $this->get(__FUNCTION__);
	}

	/**
	 * Get a list of currencies
	 */
	public function currency() {
		return $this->get(__FUNCTION__);
	}

	public function get_positions_v2($ticker) {
		$options = [
			$this->options['fiat_currency'],
			$this->options['locale'],
			$this->options['use_alias']
		];
		return $this->get($this->appendKey(__FUNCTION__) . '/' . $ticker, $options);
	}

	public function get_combined_position($ticker) {
		$options = [
			$this->options['fiat_currency'],
			$this->options['locale'],
			$this->options['use_alias']
		];
		return $this->get($this->appendKey(__FUNCTION__) . '/' . $ticker, $options);
	}

	public function marketdetails_v2($exchange, $ticker) {
		$options = [
			$this->options['fiat_currency'],
			$this->options['locale'],
			$this->options['use_alias']
		];
		return $this->get($this->appendKey(__FUNCTION__) . '/' . $exchange . '/' . $ticker, $options);
	}

	public function get_all_positions() {
		$options = [
			$this->options['fiat_currency'],
			$this->options['locale'],
			$this->options['use_alias']
		];
		return $this->get($this->appendKey(__FUNCTION__), $options);
	}

	public function candlestick($exchange, $ticker, $duration) {
		$options = [
			$this->options['fiat_currency'],
			$this->options['locale'],
			$this->options['use_alias']
		];
		return $this->get($this->appendKey(__FUNCTION__) . '/' . $exchange . '/' . $ticker . '/' . $duration, $options);
	}

	public function orderbook($exchange, $ticker) {
		$options = [
			$this->options['fiat_currency'],
			$this->options['locale'],
			$this->options['use_alias']
		];
		return $this->get(__FUNCTION__ . '/' . $exchange . '/' . $ticker, $options);
	}

	private function get($endpoint, $params = []) {
		$response = $this->client->get($endpoint, ['query' => $params]);
		$body = json_decode($response->getBody()->getContents());
		if ($body->success !== true) {
			throw new UnsuccessfulResponse(print_r($body, true));
		}
		return $body;
	}

	private function appendKey($endpoint) {
		return $endpoint . '/' . $this->key;
	}
}
