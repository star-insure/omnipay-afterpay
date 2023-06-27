# Star Insure - Omnipay-Afterpay

**AfterPay driver for the Omnipay PHP payment processing library for Star Insure Client Apps**


[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP. This package implements common classes required by Omnipay.

## Installation
Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "star-insure/omnipay-afterpay": "^1.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Quirks

- User Agent is required by Afterpay

## Reference

- [mediabeastnz/omnipay-afterpay](https://github.com/mediabeastnz/omnipay-afterpay)
- [thephpleague/omnipay-paymentexpress](https://github.com/thephpleague/omnipay-paymentexpress)
