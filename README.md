# Blockfolio SDK for PHP - Unofficial

[![Total Downloads](https://img.shields.io/packagist/dt/loonpwn/php-blockfolio.svg?style=flat)](https://packagist.org/packages/loonpwn/php-blockfolio)
[![Apache 2 License](https://img.shields.io/packagist/l/aws/aws-sdk-php.svg?style=flat)](http://aws.amazon.com/apache-2-0/)


The **Blockfolio SDK for PHP** is an interface for interacting with the Blockfolio
endpoints.


## Features

* Majority of Blockfolio endpoints mapped out 
* Built on Guzzle, middleware for handling the required headers
* Full behat automated testing to ensure endpoints are working as expected
* Docker workspace for easy development

## Getting Started

1. **Get your API Key** – This can be found within the app under Settings -> Token 
1. **Minimum requirements** – To run the SDK, your system will need to meet the
   minimum requirements, including having **PHP >= 7.1**.
1. **Install the SDK** – Using Composer run `composer require loonpwn/blockfolio-php`
1. **Using the SDK** – The best way to become familiar with how to use the SDK
   is to read the [User Guide][docs-guide]. The
   [Getting Started Guide][docs-quickstart] will help you become familiar with
   the basic concepts.

## Quick Examples

### Create an Amazon S3 client

```php
<?php
// Require the Composer autoloader.
require 'vendor/autoload.php';

use Aws\S3\S3Client;

// Instantiate an Amazon S3 client.
$s3 = new S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);
```

### Upload a file to Amazon S3

```php
<?php
// Upload a publicly accessible file. The file size and type are determined by the SDK.
try {
    $s3->putObject([
        'Bucket' => 'my-bucket',
        'Key'    => 'my-object',
        'Body'   => fopen('/path/to/file', 'r'),
        'ACL'    => 'public-read',
    ]);
} catch (Aws\S3\Exception\S3Exception $e) {
    echo "There was an error uploading the file.\n";
}
```

