# Omnipay: Setefi

**Setefi gateway for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/omnipay/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Setefi support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "simotod/omnipay-setefi": "dev-master"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

For general usage instructions, please see the main [Omnipay](https://github.com/omnipay/omnipay)
repository.

## Example

```php

	$gateway = Omnipay::create('\SimoTod\OmnipaySetefi\Gateway');

	$request = $gateway->purchase(
 		array(
            'id'                    => '99999999',
            'password'              => '99999999',
            'amount'                => '1.00',
            'returnUrl'             => 'http://www.merchant.it/notify',
            'cancelUrl'             => 'http://www.merchant.it/error',
            'transactionId'         => 'TRCK0001',
            'description'           => 'Description'
            'language'              => \SimoTod\OmnipaySetefi\Gateway::LANG_ITA
 		)
	);

	//Set test mode. Remove this row or set to false in production.
	$request->setTestMode(true);

	$response = $request->send();

	if ($response->isRedirect()) {
		// (optional) save the $response->getTransactionReference() token.
        // redirect to offsite payment Setefi
        $response->redirect();
    } else {
        // payment failed: display message to customer
        echo $response->getMessage();
    }

```

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/Cardgate/omnipay-cardgate/issues).