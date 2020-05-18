# Two Factor Auth

A PHP library for RFC 4226 and RFC 6238.

[![Latest Stable Version](https://img.shields.io/packagist/v/lorddashme/php-two-factor-auth.svg?style=flat-square)](https://packagist.org/packages/lorddashme/php-two-factor-auth) [![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.6-8892BF.svg?style=flat-square)](https://php.net/) [![Build Status](https://img.shields.io/travis/LordDashMe/php-two-factor-auth/master.svg?style=flat-square)](https://travis-ci.org/LordDashMe/php-two-factor-auth) [![Coverage Status](https://img.shields.io/coveralls/LordDashMe/php-two-factor-auth/master.svg?style=flat-square)](https://coveralls.io/github/LordDashMe/php-two-factor-auth?branch=master)

## Requirement(s)

- PHP version from 5.6.* up to latest.

## Install

- Recommended to install using Composer. Use the command below to install the package:

```text
composer require lorddashme/php-two-factor-auth
```

## Usage

### HOTP

- To generate HMAC-based One-Time Password algorithm:

```php
<?php

require __DIR__  . '/vendor/autoload.php';

use LordDashMe\TwoFactorAuth\RFC4226\HOTP;
use LordDashMe\TwoFactorAuth\Utility\Base32;

$hotp = new HOTP(Base32::encode('P@ssw0rd!'));
$hotp->setLength(6)
     ->prepare()
     ->generate();

echo $hotp->get(); // 444555
```

- To verify the generated HOTP:

```php
<?php

require __DIR__  . '/vendor/autoload.php';

use LordDashMe\TwoFactorAuth\RFC4226\HOTP;
use LordDashMe\TwoFactorAuth\Utility\Base32;

$hotp = new HOTP(Base32::encode('P@ssw0rd!'));
$hotp->setLength(6)
     ->prepare();

echo $hotp->verify('444555'); // true
```

### TOTP

- To generate Time-Based One-Time Password algorithm:

```php
<?php

require __DIR__  . '/vendor/autoload.php';

use LordDashMe\TwoFactorAuth\RFC6238\TOTP;
use LordDashMe\TwoFactorAuth\Utility\Base32;

$totp = new TOTP(Base32::encode('P@ssw0rd!'));
$totp->setTimeRemainingInSeconds(30)
     ->setTimeAdjustments(10)
     ->setLength(6)
     ->prepare()
     ->generate();

echo $totp->get(); // 552344
```

- To verify the generated TOTP:

```php
<?php

require __DIR__  . '/vendor/autoload.php';

use LordDashMe\TwoFactorAuth\RFC6238\TOTP;
use LordDashMe\TwoFactorAuth\Utility\Base32;

$totp = new TOTP(Base32::encode('P@ssw0rd!'));
$totp->setTimeRemainingInSeconds(30)
     ->setTimeAdjustments(10)
     ->setLength(6)
     ->prepare();

echo $totp->verify('552344'); // true
```

### Google Authenticator Barcode Generation

- To generate a barcode image that will use by the Google Authenticator mobile app:

```php
<?php

require __DIR__  . '/vendor/autoload.php';

use LordDashMe\TwoFactorAuth\Utility\Base32;
use LordDashMe\TwoFactorAuth\GoogleAuthenticator\BarcodeURL;
use LordDashMe\TwoFactorAuth\GoogleAuthenticator\TOTPFormat;

$secret = Base32::encode('P@ssw0rd!', false);
$accountUser = 'reyesjoshuaclifford@gmail.com';
$issuer = 'TwoFactorAuth';
$digits = 6;
$period = 30;

$totpFormat = new TOTPFormat($period);
$barcodeURL = new BarcodeURL($secret, $accountUser, $issuer, $totpFormat);
$barcodeURL->setAlgorithm('SHA1')
           ->setDigits($digits)
           ->build();

echo $barcodeURL->get(); // https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=otpauth://totp/TwoFactorAuth:reyesjoshuaclifford@gmail.com?secret=KBAHG43XGBZGIII&algorithm=SHA1&digits=6&period=30&issuer=TwoFactorAuth
```

## Other Reference

- [What's the difference between HOTP and TOTP?](https://www.microcosm.com/blog/hotp-totp-what-is-the-difference)

- [RFC 4226](https://tools.ietf.org/html/rfc4226)

- [RFC 6238](https://tools.ietf.org/html/rfc6238)

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
