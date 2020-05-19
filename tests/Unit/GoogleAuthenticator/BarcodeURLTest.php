<?php

namespace LordDashMe\TwoFactorAuth\Tests\Unit\GoogleAuthenticator;

use Mockery as Mockery;
use PHPUnit\Framework\TestCase;

use LordDashMe\TwoFactorAuth\Utility\Base32;
use LordDashMe\TwoFactorAuth\GoogleAuthenticator\BarcodeURL;
use LordDashMe\TwoFactorAuth\GoogleAuthenticator\HOTPFormat;
use LordDashMe\TwoFactorAuth\GoogleAuthenticator\TOTPFormat;

class BarcodeURLTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_construct_barcode_url_with_issuer_and_hotp_format()
    {
        $hotpFormat = new HOTPFormat(1);

        $barcodeURL = new BarcodeURL(
            Base32::encode('P@ssw0rd!'), 
            'reyesjoshuaclifford@gmail.com', 
            'TwoFactorAuth',
            $hotpFormat 
        );

        $barcodeURL->setAlgorithm('sha1')
                   ->setDigits(6)
                   ->build();

        $this->assertEquals(
            'https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=otpauth://hotp/TwoFactorAuth:reyesjoshuaclifford@gmail.com?secret=KBAHG43XGBZGIII%3D&algorithm=SHA1&digits=6&counter=1&issuer=TwoFactorAuth',
            $barcodeURL->get()
        );
    }

    /**
     * @test
     */
    public function it_should_construct_barcode_url_with_issuer_and_totp_format()
    {
        $totpFormat = new TOTPFormat(30);

        $barcodeURL = new BarcodeURL(
            Base32::encode('P@ssw0rd!'), 
            'reyesjoshuaclifford@gmail.com', 
            'TwoFactorAuth',
            $totpFormat 
        );

        $barcodeURL->setAlgorithm('sha1')
                   ->setDigits(6)
                   ->build();

        $this->assertEquals(
            'https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=otpauth://totp/TwoFactorAuth:reyesjoshuaclifford@gmail.com?secret=KBAHG43XGBZGIII%3D&algorithm=SHA1&digits=6&period=30&issuer=TwoFactorAuth',
            $barcodeURL->get()
        );
    }

    /**
     * @test
     */
    public function it_should_construct_barcode_url_with_totp_format()
    {
        $totpFormat = new TOTPFormat(30);

        $barcodeURL = new BarcodeURL(
            Base32::encode('P@ssw0rd!'), 
            'reyesjoshuaclifford@gmail.com', 
            '',
            $totpFormat 
        );

        $barcodeURL->setAlgorithm('SHA1')
                   ->setDigits(6)
                   ->build();

        $this->assertEquals(
            'https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=otpauth://totp/reyesjoshuaclifford@gmail.com?secret=KBAHG43XGBZGIII%3D&algorithm=SHA1&digits=6&period=30',
            $barcodeURL->get()
        );
    }
}
