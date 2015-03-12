## blockscore-php [![Travis Build Status](https://travis-ci.org/BlockScore/blockscore-php.svg?branch=master)](https://travis-ci.org/BlockScore/blockscore-php)

This is the official PHP client library of the [BlockScore API](https://blockscore.com). You can sign up for an account on [our dashboard](https://manage.blockscore.com/signup).

### Requirements

PHP 5.3.29 and later.

### Composer

This library [can be used through Composer](https://packagist.org/packages/blockscore/blockscore-php). To get started, add the following to your `composer.json`:

```json
{
  "require": {
    "blockscore/blockscore-php": "4.*"
  }
}
```

Make sure to install the changes:

```
composer.phar install
```

Make sure that Composer autoload is added somewhere in your project like so:

```
require_once('vendor/autoload.php');
```

### Manual Installation

If you would rather manually include the library in your project, you can [download this repository](https://github.com/BlockScore/blockscore-php/archive/master.zip) and add the following line to your project:

```
require_once('/path/to/blockscore-php/init.php');
```

### Getting Started

This is an example of creating a person verification and setting up the library:

```php
\BlockScore\BlockScore::setApiKey('sk_test_11111111111111111111111111111111');

$person = \BlockScore\Person::create(array(
    'name_first' => 'Jane',
    'name_last' => 'Doe',
    'birth_day' => 1,
    'birth_month' => 1,
    'birth_year' => 1990,
    'document_type' => 'ssn',
    'document_value' => '0000',
    'address_street1' => '123 Something Ave',
    'address_city' => 'Newton Falls',
    'address_subdivision' => 'OH',
    'address_postal_code' => '44444',
    'address_country_code' => 'US',
));

var_dump($person);
```

### Documentation

Our [API Reference](http://docs.blockscore.com/php) contains all of the code examples for this library.

### Tests

If you want to run our test suite, please install [PHPUnit](https://packagist.org/packages/phpunit/phpunit) using [Composer](https://getcomposer.org/)

```
composer.phar update --dev
```

To run the client lib's test suite, run

```
./vendor/bin/phpunit
```

### Credit

This library is largely based on the wonderful [stripe-php](https://github.com/stripe/stripe-php) library.
