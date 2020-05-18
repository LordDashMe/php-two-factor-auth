
<?php

use Mockery as Mockery;
use PHPUnit\Framework\TestCase;

use LordDashMe\TwoFactorAuth\RFC4226\HOTP;
use LordDashMe\TwoFactorAuth\Utility\Base32;

class HOTPTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_generate_hotp()
    {
        $hotp = new HOTP(Base32::encode('P@ssw0rd!'));
        $hotp->prepare()
            ->generate();

        $this->assertTrue(strlen($hotp->get()) === 6);
    }
}
