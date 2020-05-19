<?php

namespace LordDashMe\TwoFactorAuth\Tests\Integration\GoogleAuthenticator;

use Mockery as Mockery;
use PHPUnit\Framework\TestCase;

use LordDashMe\TwoFactorAuth\RFC6238\TOTP;
use LordDashMe\TwoFactorAuth\Utility\Base32;
use LordDashMe\TwoFactorAuth\GoogleAuthenticator\BarcodeURL;
use LordDashMe\TwoFactorAuth\GoogleAuthenticator\TOTPFormat;

class GenerateBarcodeURLTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_generate_barcode_url_for_google_authenticator()
    {
        $secret = Base32::encode('P@ssw0rd!', false);
        $accountUser = 'reyesjoshuaclifford@gmail.com';
        $issuer = 'TwoFactorAuth';
        $digits = 6;
        $period = 30;

        $totp = new TOTP($secret);

        $totp->setTimeZone('Asia/Manila')
             ->setTimeRemainingInSeconds($period)
             ->setTimeAdjustments(10)
             ->setLength($digits)
             ->prepare()
             ->generate();

        // var_dump($totp->get());

        $format = new TOTPFormat($period);

        $barcodeURL = new BarcodeURL($secret, $accountUser, $issuer, $format);

        $barcodeURL->setAlgorithm('sha1')
                   ->setDigits($digits)
                   ->build();

        $this->assertEquals('https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=otpauth://totp/TwoFactorAuth:reyesjoshuaclifford@gmail.com?secret=KBAHG43XGBZGIII&algorithm=SHA1&digits=6&period=30&issuer=TwoFactorAuth', $barcodeURL->get());
    }
}
