<?php

namespace LordDashMe\TwoFactorAuth\Tests\Unit\RFC6238;

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
        
        $totp->setTimeZone('Asia/Manila')
             ->setTimeRemainingInSeconds(30)
             ->setTimeAdjustments(0)
             ->setLength(6)
             ->prepare()
             ->generate();

        $this->assertTrue(strlen($totp->get()) === 6);
    }
}
