<?php

use Mockery as Mockery;
use PHPUnit\Framework\TestCase;

use LordDashMe\TwoFactorAuth\RFC6238\TOTP;
use LordDashMe\TwoFactorAuth\Utility\Base32;

class TOTPTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_generate_totp()
    {
        $totp = new TOTP(Base32::encode('P@ssw0rd!'));
        $totp->setTimeRemainingInSeconds(30)
            ->setTimeAdjustments(10)
            ->setLength(6)
            ->prepare()
            ->generate();

        $this->assertTrue(strlen($totp->get()) === 6);
    }
}
