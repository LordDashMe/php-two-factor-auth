<?php

use Mockery as Mockery;
use PHPUnit\Framework\TestCase;

use LordDashMe\TwoFactorAuth\RFC6238\TOTP;
use LordDashMe\TwoFactorAuth\Utilities\Base32;
use LordDashMe\TwoFactorAuth\GoogleAuthenticator\BarcodeURL;
use LordDashMe\TwoFactorAuth\GoogleAuthenticator\TOTPFormat;

class GoogleAuthenticatorTOTPTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_generate_google_authenticator_required_barcode()
    {
        $secret = Base32::encode('P@ssw0rd!', false);
        $accountUser = 'reyesjoshuaclifford@gmail.com';
        $issuer = 'TwoFactorAuth';
        $digits = 6;
        $period = 30;

        $totp = new TOTP($secret);
        $totp->setTimeRemainingInSeconds($period);
        $totp->setTimeAdjustments(10);
        $totp->prepare();
        $totp->setLength($digits);
        $totp->generate();

        // var_dump($totp->get());

        $totpFormat = new TOTPFormat($period);
        $barcodeURL = new BarcodeURL($secret, $accountUser, $issuer, $totpFormat);
        $barcodeURL->setAlgorithm('SHA1');
        $barcodeURL->setDigits($digits);
        $barcodeURL->build();

        $this->assertEquals('https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=otpauth://totp/TwoFactorAuth:reyesjoshuaclifford@gmail.com?secret=KBAHG43XGBZGIII&algorithm=SHA1&digits=6&period=30&issuer=TwoFactorAuth', $barcodeURL->get());
    }
}
