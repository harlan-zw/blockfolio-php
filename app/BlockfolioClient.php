<?php

namespace Blockfolio;

use Blockfolio\Exception\MissingMagicException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class BlockfolioClient extends Client {

	private const BUILD_NUMBER = 225;
	private const BUILD_VERSION = '1.1.10.225';
	private const BASE_URI = 'https://api-v0.blockfolio.com/rest/';
	private const DEFAULT_USER_AGENT = 'okhttp/3.6.0';

	private const DEFAULT_HEADERS = [
		'User-Agent'   => self::DEFAULT_USER_AGENT,
		'build-number' => self::BUILD_NUMBER,
		'version'      => self::BUILD_VERSION,
		'Accept-Encoding' => 'gzip',
		'Connection' => 'Keep-Alive'
	];

	/**
	 * BlockfolioClient constructor.
	 */
	public function __construct(array $options = []) {

		if (empty($options['BLOCKFOLIO_MAGIC'])) {
			throw new MissingMagicException('You must provide your magic for blockfolio to work.');
		}

		$stack = new HandlerStack();
		$stack->setHandler(new CurlHandler());

		// add our default headers
		$stack->push(Middleware::mapRequest(function (RequestInterface $request) use ($options) {
			foreach (self::DEFAULT_HEADERS as $header => $val) {
				$request = $request->withHeader($header, $val);
			}
			$request = $request->withHeader('magic', $options['BLOCKFOLIO_MAGIC']);
			return $request;
		}), 'add_default_headers');

		// handle unsuccessful requests
		$stack->push(Middleware::mapResponse(function (ResponseInterface $response) {
			if ($response->getStatusCode() !== 200) {
				throw new \Exception('Unknown response from Blockfolio ' . $response->getStatusCode());
			}
			return $response;
		}), 'check_unsuccessful');

		parent::__construct(array_merge($options, [
			'base_uri' => self::BASE_URI,
			'handler'  => $stack
		]));

	}
}
