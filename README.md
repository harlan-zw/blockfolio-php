# Blockfolio SDK for PHP - Unofficial

[![Total Downloads](https://img.shields.io/packagist/dt/loonpwn/blockfolio-php.svg?style=flat)](https://packagist.org/packages/loonpwn/php-blockfolio)
[![Apache 2 License](https://img.shields.io/packagist/l/loonpwn/blockfolio-php.svg?style=flat)](http://aws.amazon.com/apache-2-0/)

## THIS IS NO LONGER WORKING FOR PUBLIC ACCESS DUE TO BLOCKFOLIO BLOCKING IT.
    HOWEVER YOU MAY USE IT FOR YOUR OWN ACCOUNT BY JUMPING THROUGH SOME HOOPS

The **Blockfolio SDK for PHP** is an interface for interacting with the Blockfolio endpoints.


## Features

* Majority of Blockfolio endpoints mapped out
* Built on Guzzle, middleware for handling the required headers
* Full behat automated testing to ensure endpoints are working as expected
* Docker workspace for easy development

## Getting Started

0. **Find Your Magic** - You will need to use Packet Capture Android App and scan Blockfolio outgoing requests. The
request looks like this

```
GET /rest/system_status?platform=android_rn HTTP/1.1
magic: edtopjhgn2345piuty89whqejfiobh89-2q453
build-number: 225
version: 1.1.10.225
Host: api-v0.blockfolio.com
Connection: Keep-Alive
Accept-Encoding: gzip
User-Agent: okhttp/3.6.0
```

1. **Get your API Key** – This can be found within the app under Settings -> Token
2. **Minimum requirements** – To run the SDK, your system will need to meet the minimum requirements, including having **PHP >= 7.1**.
3. **Install the SDK** – Using Composer run `composer require loonpwn/blockfolio-php`
4. **Using the SDK** – Follow the examples below or look at the functions available

## Endpoints

The base URL is `https://api-v0.blockfolio.com/rest/`.

* `version` - Gets the API version
* `system_status` - Gets the API's system status
* `coinlist_v6` - Gets a list of all coins available
* `currency` - Gets a list of currencies available
* `get_all_positions` - Gets a list of all your positions
* `get_positions_v2/{ticker}` - Gets all of your positions for a ticker
* `get_combined_position/{ticker}` - Similar to the above
* `marketdetails_v2/{exchange}/{ticker}` - See what an exchange is trading a ticker for.  _Binance used by default_
* `candlestick/{exchange}/{ticker}` - Get all data points for a ticker on an exchange.  _Binance used by default_
* `orderbook/{exchange}/{ticker}` - Get the order book for a ticker on an exchange. _Binance used by default_

## Quick Examples

### Create the client

The ideal setup is to create an environment variable. Alternatively you can pass in the options the api key.

```
BLOCKFOLIO_API_KEY=<key>
BLOCKFOLIO_MAGIC=<magic>
```


```php
<?php
// Require the Composer autoloader.
require 'vendor/autoload.php';

use Blockfolio\API;

// Instantiate a blockfolio api instance
$api = new API([
    'BLOCKFOLIO_MAGIC' => '<magic',  // if not declared as an environment variabl
    'BLOCKFOLIO_API_KEY' => '<key>', // if not declared as an environment variable
    'fiat_currency' => 'USD', // optional
    'locale' => 'en-US', // optional
    'use_alias' => true, // optional
]);
```

### Get all positions

```php
<?php
$response = $api->get_all_positions();
// display all positions
var_dump($response->positionList);
```

