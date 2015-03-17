Tomaj/PHPpayments
===========================

Requirements
------------

Tomaj/PHPPayments requires PHP 5.3.0 or higher.

If you would like to use wrapper classes from Tomaj\Epayment namespace you will need define some constants. You can find example in src/example_constants.php which is using monogram [epayment simulator](http://epaymentsimulator.monogram.sk).

Installation
------------

The best way to install Tomaj/PHPpayemnts is using  [Composer](http://getcomposer.org/):

```sh
$ composer require tomaj/php-payments
```

Background
----------

This library is base on MONOGRAM epayment. I created this repository for composer to simplify adding this library to projects. You can found more information about library in [http://epayment.monogram.sk](http://epayment.monogram.sk) or in my other forked github repository here (https://github.com/tomaj/EPayment)[https://github.com/tomaj/EPayment].


ComforPay
---------

For using ComfortPay you need to define this constants:

TB_COMFORTPAY_MID  - constant from tatrabanka (use like tatrapay)
TB_COMFORTPAY_WS_MID - constant from tatrabanka
TB_COMFORTPAY_SHAREDSECRET - constant from tatrabanka (use like tatrapay)
TB_COMFORTPAY_REDIRECTURLBASE - same usage as tatrapay
TB_COMFORTPAY_TERMINALID - number from tatrabanka
TB_COMFORTPAY_LOCAL_CERT_PATH - cert file path
TB_COMFORTPAY_PASSPHRASE_PATH - path to file with passprase for cert
TB_COMFORTPAY_REM - email aaddress

-----

Repository [http://github.com/tomaj/php-payments](http://github.com/tomaj/php-payments).
