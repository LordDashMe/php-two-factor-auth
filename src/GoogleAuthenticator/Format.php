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

/**
 * Format for the Google Authenticator Barcode URL.
 * 
 * @author Joshua Clifford Reyes <reyesjoshuaclifford@gmail.com>
 */
interface Format 
{
    /**
     * The getter method for the type class property.
     * 
     * @return string
     */
    public function getType();

    /**
     * The getter method for the parameter class property.
     * 
     * @return array
     */
    public function getParameters();
}
