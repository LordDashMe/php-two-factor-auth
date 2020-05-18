<?php

/*
 * This file is part of the  Two Factor Auth.
 *
 * (c) Joshua Clifford Reyes <reyesjoshuaclifford@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this surce code.
 */

namespace LordDashMe\TwoFactorAuth\RFC4226;

use LordDashMe\TwoFactorAuth\OTP;

/**
 * An HMAC-Based One-Time Password Algorithm.
 * 
 * @author Joshua Clifford Reyes <reyesjoshuaclifford@gmail.com>
 */
class HOTP extends OTP 
{
    /**
     * The max number of verification for the generated one-time password.
     * 
     * @var int
     */
    protected $maxVerificationNumber = 3;

    public function __construct($secret)
    {
        parent::__construct($secret);
    }

    /**
     * Prepare HOTP required setup.
     * 
     * @return $this
     */
    public function prepare()
    {
        $this->setCounter(1);

        return $this;
    }
}
