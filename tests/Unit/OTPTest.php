<?php

namespace LordDashMe\TwoFactorAuth\Tests\Unit;

use Mockery as Mockery;
use PHPUnit\Framework\TestCase;

use LordDashMe\TwoFactorAuth\OTP;
use LordDashMe\TwoFactorAuth\Utility\Base32;

class OTPTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_generate_otp()
    {
        $otp = new OTP(Base32::encode('P@ssw0rd!'));
        
        $otp->setLength(6)
            ->setCounter(1)
            ->setAlgorithm('sha1')
            ->generate();

        $this->assertEquals('238681', $otp->get());
    }

    /**
     * @test
     */
    public function it_should_verify_valid_otp()
    {
        $otp = new OTP(Base32::encode('P@ssw0rd!'));
        
        $otp->setLength(6)
            ->setCounter(1)
            ->setAlgorithm('sha1');

        $this->assertTrue($otp->verify('238681'));
    }

    /**
     * @test
     */
    public function it_should_verify_invalid_otp()
    {
        $otp = new OTP(Base32::encode('P@ssw0rd!'));
        
        $otp->setLength(6)
            ->setCounter(1)
            ->setAlgorithm('sha1');

        $this->assertTrue(! $otp->verify('1'));
    }
}
