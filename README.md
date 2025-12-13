# sms-client
A generic SMS client library. Supports multiple swappable drivers, so that you're never tied to just one provider.

This library is aimed squarely at sending SMS messages only, and I don't plan to add support for other functionality. The idea is to create one library that should be able to work with any provider that has a driver for the purpose of sending SMS messages.

Fork note
---------

This project is a fork of `Matthewbdaly\SMS`. It is maintained and improved by David Běhal (`DejwCake`).

Namespace change
----------------

The library namespace has been updated from `Matthewbdaly\SMS` to `DejwCake\SmsClient`. Backward compatibility with the old namespace has been removed; please update your imports accordingly.

Drivers
-------

It currently ships with the following drivers:

* Clockwork
* Nexmo
* TextLocal
* Twilio
* AWS SNS (requires installation of `aws/aws-sdk-php`)
* Mail (for mail-to-SMS gateways)
* O2SK (O2 Slovakia)

In addition, it also has the following drivers for test purposes:

* RequestBin
* Null
* Log

The RequestBin sends the POST request to the specified RequestBin path for debugging. The Null driver does nothing, while the Log driver accepts a PSR3 logger and uses it to log the request.

Example Usage
-----

**Null**

```php
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use DejwCake\SmsClient\Drivers\NullDriver;
use DejwCake\SmsClient\Client;

$guzzle = new GuzzleClient;
$resp = new Response;
$driver = new NullDriver($guzzle, $resp);
$client = new Client($driver);
$msg = [
    'to'      => '+44 01234 567890',
    'content' => 'Just testing',
];
$client->send($msg);
```

**Log**

```php
use DejwCake\SmsClient\Drivers\Log;
use DejwCake\SmsClient\Client;
use Psr\Log\LoggerInterface;

$driver = new Log($logger); // $logger should be an implementation of Psr\Log\LoggerInterface
$client = new Client($driver);
$msg = [
    'to'      => '+44 01234 567890',
    'content' => 'Just testing',
];
$client->send($msg);

```

**RequestBin**

```php
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use DejwCake\SmsClient\Drivers\RequestBin;
use DejwCake\SmsClient\Client;

$guzzle = new GuzzleClient;
$resp = new Response;
$driver = new RequestBin($guzzle, $resp, [
    'path' => 'MY_REQUESTBIN_PATH',
]);
$client = new Client($driver);
$msg = [
    'to'      => '+44 01234 567890',
    'content' => 'Just testing',
];
$client->send($msg);
```

**Clockwork**

```php
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use DejwCake\SmsClient\Drivers\Clockwork;
use DejwCake\SmsClient\Client;

$guzzle = new GuzzleClient;
$resp = new Response;
$driver = new Clockwork($guzzle, $resp, [
    'api_key' => 'MY_CLOCKWORK_API_KEY',
]);
$client = new Client($driver);
$msg = [
    'to'      => '+44 01234 567890',
    'content' => 'Just testing',
];
$client->send($msg);
```

**Nexmo**

```php
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use DejwCake\SmsClient\Drivers\Nexmo;
use DejwCake\SmsClient\Client;

$guzzle = new GuzzleClient;
$resp = new Response;
$driver = new Nexmo($guzzle, $resp, [
    'api_key' => 'MY_NEXMO_API_KEY',
    'api_secret' => 'MY_NEXMO_API_SECRET',
]);
$client = new Client($driver);
$msg = [
    'to'      => '+44 01234 567890',
    'from'    => 'Test User',
    'content' => 'Just testing',
];
$client->send($msg);
```

**AWS SNS**

```php
use DejwCake\SmsClient\Client;
use DejwCake\SmsClient\Drivers\Aws;

$config = [
    'api_key'    => 'foo',
    'api_secret' => 'bar',
    'api_region' => 'ap-southeast-2'
];
$driver = new Aws($config);
$client = new Client($driver);
$msg = [
    'to'      => '+44 01234 567890',
    'from'    => 'Test User',
    'content' => 'Just testing',
];
$client->send($msg);
```

**Mail**

```php
use DejwCake\SmsClient\Client;
use DejwCake\SmsClient\Drivers\Mail;
use DejwCake\SmsClient\Contracts\Mailer;

$config = [
    'domain' => 'my.sms-gateway.com'
];
$driver = new Mail($mailer, $config);
$client = new Client($driver);
$msg = [
    'to'      => '+44 01234 567890',
    'content' => 'Just testing',
];
$client->send($msg);
```

**TextLocal**

```php
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use DejwCake\SmsClient\Drivers\TextLocal;
use DejwCake\SmsClient\Client;

$guzzle = new GuzzleClient;
$resp = new Response;
$driver = new TextLocal($guzzle, $resp, [
    'api_key' => 'MY_TEXTLOCAL_API_KEY',
]);
$client = new Client($driver);
$msg = [
    'to'      => '+44 01234 567890',
    'from'    => 'Test User',
    'content' => 'Just testing',
];
$client->send($msg);
```

**Twilio**

```php
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use DejwCake\SmsClient\Drivers\Twilio;
use DejwCake\SmsClient\Client;

$guzzle = new GuzzleClient;
$resp = new Response;
$driver = new Twilio($guzzle, $resp, [
    'account_id' => 'MY_TWILIO_ACCOUNT_ID',
    'api_token' => 'MY_TWILIO_API_TOKEN',
]);
$client = new Client($driver);
$msg = [
    'to'      => '+44 01234 567890',
    'from'      => '+44 01234 567890',
    'content' => 'Just testing',
];
$client->send($msg);
```

**O2SK**

```php
use GuzzleHttp\Client as GuzzleClient;
use DejwCake\SmsClient\Drivers\O2SK;
use DejwCake\SmsClient\Client;

$driver = new O2SK(new GuzzleClient, [
    'apiKey' => 'MY_O2SK_API_KEY',
]);
$client = new Client($driver);
$msg = [
    'message' => 'Testing message',
    'sender' => ['text' => 'Tester'],
    'recipients' => [
        ['phonenr' => '+421911000000']
    ]
];
$client->send($msg);
```

Mail driver
-----------

I have implemented a mail driver at `DejwCake\SmsClient\Drivers\Mail`, but it's very basic and may not work with a lot of mail-to-SMS gateways out of the box. It accepts an instance of the `DejwCake\SmsClient\Contracts\Mailer` interface as the first argument, and the config array as the second.

I've included the class `DejwCake\SmsClient\PHPMailAdapter` in the library as a very basic implementation of the mailer interface, but it's deliberately very basic - it's just a very thin wrapper around the PHP `mail()` function. You will almost certainly want to create your own implementation for your own use case - for instance, if you're using Laravel you might create a wrapper class for the `Mail` facade.

The mail driver will nearly always be slower and less reliable than the HTTP-based ones, so if you have to integrate with a provider that doesn't yet have a driver, but does have a REST API, you're probably better off creating an API driver for it. If you do need to work with a mail-to-SMS gateway, you're quite likely to find that you need to extend `DejwCake\SmsClient\Drivers\Mail` to amend the functionality.

Laravel and Lumen integration
-------------------

Using Laravel or Lumen? You probably want to use [my integration package](https://packagist.org/packages/dejwcake/laravel-sms) rather than this one, since that includes a service provider, as well as the `SMS` facade and easier configuration.

Creating your own driver
------------------------

It's easy to create your own driver - just implement the `DejwCake\SmsClient\Contracts\Driver` interface. You can use whatever method is most appropriate for sending the SMS - for instance, if your provider has a mail-to-SMS gateway, you can happily use Swiftmailer or PHPMailer in your driver to send emails, or if they have a REST API you can use Guzzle.

You can pass any configuration options required in the `config` array in the constructor of the driver. Please ensure that your driver has tests using PHPUnit and that it meets the coding standard (the package includes a PHP Codesniffer configuration for that reason).

If you've created a new driver, feel free to submit a pull request and I'll consider including it.

## How to develop this project

### Composer

Update dependencies:
```shell
docker compose run --rm cli composer update
```

Composer normalization:
```shell
docker compose run --rm php-qa composer normalize
```

### Run tests

Run tests with pcov:
```shell
docker compose run --rm test ./vendor/bin/phpunit -d pcov.enabled=1
```

### Run code analysis tools (php-qa)

PHP compatibility:
```shell
docker compose run --rm php-qa phpcs --standard=.phpcs.compatibility.xml --cache=.phpcs.cache
```

Code style:
```shell
docker compose run --rm php-qa phpcs -s --colors --extensions=php
```

Fix style issues:
```shell
docker compose run --rm php-qa phpcbf -s --colors --extensions=php
```

Static analysis (phpstan):
```shell
docker compose run --rm php-qa phpstan analyse --configuration=phpstan.neon
```

Mess detector (phpmd):
```shell
docker compose run --rm php-qa phpmd ./src,./tests ansi phpmd.xml --suffixes php --baseline-file phpmd.baseline.xml
```
