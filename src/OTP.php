<?php

/*
 * This file is part of the  Two Factor Auth.
 *
 * (c) Joshua Clifford Reyes <reyesjoshuaclifford@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this surce code.
 */

namespace LordDashMe\TwoFactorAuth;

use LordDashMe\TwoFactorAuth\Utility\Base32;

/**
 * One-Time Password Algorithm.
 * 
 * @author Joshua Clifford Reyes <reyesjoshuaclifford@gmail.com>
 */
class OTP 
{
    /**
     * The max number of verification for the generated one-time password.
     * 
     * @var int
     */
    protected $maxVerificationNumber = 1;

    /**
     * The inputed secret string that will be use to generate one-time password.
     * 
     * @var string
     */
    private $secret = '';

    /**
     * The size of the generated one-time password.
     * 
     * @var int
     */
    private $length = 6;

    /**
     * The randomness counter for the generated password one-time password.
     * The value will depend on the sub class.
     * 
     * @var null
     */
    private $counter = null;

    /**
     * The algorithm to be use for the hash_hmac.
     * 
     * @var string
     */
    private $algorithm = 'sha1';

    /**
     * The generated one-time password.
     * 
     * @var string
     */
    private $generatedOTP = '';

    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    /**
     * The setter method for the length class property.
     * 
     * @param  int $length  The length to be use to generate OTP.
     * 
     * @return $this
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * The setter method for the counter class property.
     * 
     * @param  int $counter  The randomness counter for the OTP.
     * 
     * @return $this
     */
    public function setCounter($counter)
    {
        $this->counter = $counter;

        return $this;
    }

    /**
     * The setter method for the algorithm class property.
     * 
     * @param  int $algorithm  The algorithm to be use for the hmac hashing.
     * 
     * @return $this
     */
    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;

        return $this;
    }

    /**
     * Generation process for the OTP.
     * 
     * @return $this
     */
    public function generate()
    {
        // Pack the counter into 64-bit int.
        // Use bitwise operators to manipulate and shift bits.
        // The 0xFFFFFFFF Hex = 4294967295 Decimal.
        $counterBytes = \pack(
            'NN', ($this->counter & (0xFFFFFFFF << 32)) >> 32, $this->counter & 0xFFFFFFFF
        );

        // Generate hmac hash based on the given counter bytes 
        // using sha1 algorithm that produce 160-bit.
        $hash = \hash_hmac($this->algorithm, $counterBytes, Base32::decode($this->secret), true);
        
        // Get the offset value in the hash data.
        // Using bitwise AND operator, if the value is more than 15 (0xF) then return 0.
        $offset = \ord($hash[19]) & 0xF;
        
        // Unpack the hmac hashed.
        $unpack = \unpack('Nint', \substr($hash, $offset, 4));
        
        // The 0x7FFFFFFF Hex = 2147483647 Decimal.
        $code = $unpack['int'] & 0x7FFFFFFF;

        $this->generatedOTP = \str_pad(
            $code % \pow(10, $this->length), $this->length, 0, STR_PAD_LEFT
        );

        return $this;
    }

    /**
     * Get the generated OTP.
     * 
     * @return string
     */
    public function get()
    {
        return $this->generatedOTP;
    }

    /**
     * Verify the generated OTP from the other sources against the current generated OTP.
     * 
     * @param  string $generatedOTP  The generated OTP from the other sources.
     * 
     * @return boolean
     */
    public function verify($generatedOTP)
    {
        for ($i = 0; $i < $this->maxVerificationNumber; $i++) {
            
            $this->generate();
            
            if ($this->get() == $generatedOTP) {
                return true;
            }

            $this->counter++;
        }

        return false;
    }
}
