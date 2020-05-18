<?php

use Mockery as Mockery;
use PHPUnit\Framework\TestCase;

use LordDashMe\TwoFactorAuth\Utility\Base32;

class Base32Test extends TestCase
{
    /**
     * @test
     */
    public function it_should_encode_base32()
    {
        $this->assertEquals('JBSWY3DPFQQFO33SNRSCC===', Base32::encode('Hello, World!'));
    }

    /**
     * @test
     */
    public function it_should_decode_base32()
    {
        $this->assertEquals('Hello, World!', Base32::decode('JBSWY3DPFQQFO33SNRSCC==='));
    }
}
