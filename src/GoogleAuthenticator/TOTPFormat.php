<?php

/*
 * This file is part of the  Two Factor Auth.
 *
 * (c) Joshua Clifford Reyes <reyesjoshuaclifford@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this surce code.
 */

namespace LordDashMe\TwoFactorAuth\GoogleAuthenticator;

use LordDashMe\TwoFactorAuth\GoogleAuthenticator\Format;

/**
 * TOTP Format for the Google Authenticator Barcode URL.
 * 
 * @author Joshua Clifford Reyes <reyesjoshuaclifford@gmail.com>
 */
class TOTPFormat implements Format
{
    /**
     * The dependecy parameters for the format type.
     * 
     * @var array
     */
    private $parameters = array();

    public function __construct($period)
    {
        $this->parameters['period'] = $period;
    }

    /**
     * The getter method for the type class property.
     * 
     * @return string
     */
    public function getType()
    {
        return 'totp';
    }

    /**
     * The getter method for the parameter class property.
     * 
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
