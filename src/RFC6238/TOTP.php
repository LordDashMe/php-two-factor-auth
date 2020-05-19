<?php

/*
 * This file is part of the Two Factor Auth.
 *
 * (c) Joshua Clifford Reyes <reyesjoshuaclifford@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LordDashMe\TwoFactorAuth\RFC6238;

use DateTime;
use DateTimeZone;
use LordDashMe\TwoFactorAuth\OTP;

/**
 * Time-Based One-Time Password Algorithm
 * 
 * @author Joshua Clifford Reyes <reyesjoshuaclifford@gmail.com>
 */
class TOTP extends OTP 
{
    /**
     * The max number of verification for the generated one-time password.
     * 
     * @var int
     */
    protected $maxVerificationNumber = 3;

    /**
     * The time zone for the TOTP.
     * 
     * @var string
     */
    private $timeZone = 'Asia/Manila';

    /**
     * The time remaining for the TOTP.
     * 
     * @var int
     */
    private $timeRemainingInSeconds = 30;
    
    /**
     * The time adjustments for the TOTP (+/-).
     * 
     * @var int
     */
    private $timeAdjustments = 0;

    public function __construct($secret)
    {
        parent::__construct($secret);
    }

    /**
     * The setter method for the time zone class property.
     * 
     * @param  string $timeZone  The time zone to be used for the TOTP.
     * 
     * @return $this
     */
    public function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    /**
     * The setter method for the time remaining in seconds class property.
     * 
     * @param  int $timeRemainingInSeconds  The max time remaining in seconds for TOTP.
     * 
     * @return $this
     */
    public function setTimeRemainingInSeconds($timeRemainingInSeconds)
    {
        $this->timeRemainingInSeconds = $timeRemainingInSeconds;

        return $this;
    }

    /**
     * The setter method for the time adjustments class property.
     * 
     * @param  int $timeAdjustments  The time to be adjust in seconds for TOTP.
     * 
     * @return $this
     */
    public function setTimeAdjustments($timeAdjustments)
    {
        $this->timeAdjustments = $timeAdjustments;

        return $this;
    }

    /**
     * (Optional) Prepare TOTP required setup.
     * Can override the $time using $this->setCounter(...)
     * 
     * @return $this
     */
    public function prepare()
    {
        $now = new DateTime();
        
        $timeZone = new DateTimeZone($this->timeZone);
        
        $now->setTimezone($timeZone);
        
        $timeFormatted = $now->format('Y-m-d H:i:s T');

        $time = \floor(
            (\strtotime($timeFormatted) + ($this->timeAdjustments)) / $this->timeRemainingInSeconds
        );

        $this->setCounter($time);

        return $this;
    }
}
