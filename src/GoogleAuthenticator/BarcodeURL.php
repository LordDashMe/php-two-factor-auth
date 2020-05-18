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
 * Barcode URL construction for Google Authenticator.
 * See: https://github.com/google/google-authenticator/
 * 
 * @author Joshua Clifford Reyes <reyesjoshuaclifford@gmail.com>
 */
class BarcodeURL
{
    /**
     * The secret value that is in BASE32 format and will be pass on the URL constructed
     * for barcode generation.
     * 
     * @var string
     */
    private $secret = '';

    /**
     * The account name to be use in the barcode.
     * 
     * @var string
     */
    private $accountName = '';

    /**
     * The issuer to be use in the barcode.
     * 
     * @var string
     */
    private $issuer = '';

    /**
     * The OTP generation format its either TOTP or HOTP.
     * 
     * @var Format|null
     */
    private $format = null;
    
    /**
     * The algorithm used in the OTP generation process and will use to send flag for the
     * google barcode generation.
     * 
     * @var string
     */
    private $algorithm = 'SHA1';
    
    /**
     * The number for digit(s) in a OTP password generation used.
     * 
     * @var int
     */
    private $digits = 6;

    /**
     * Constructed URL to be use to construct barcode.
     * 
     * @var string
     */
    private $constructedURL = '';

    public function __construct($secret, $accountName, $issuer, Format $format)
    {
        $this->secret = $secret;
        $this->accountName = $accountName;
        $this->issuer = $issuer;
        $this->format = $format;
    }

    /**
     * The setter method for the algorithm class property.
     * 
     * @param string $algorithm  The algo to be pass on the URL construction.
     * 
     * @return $this
     */
    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;

        return $this;
    }

    /**
     * The setter method for the digits class property.
     * 
     * @param string $digits  The digits to be pass on the URL construction.
     * 
     * @return $this
     */
    public function setDigits($digits)
    {
        $this->digits = $digits;

        return $this;
    }

    /**
     * Construct URL based on the requirements posted on
     * https://github.com/google/google-authenticator/wiki/Key-Uri-Format
     * 
     * @return $this
     */
    public function build()
    {
        $parameters = array(
            'secret' => $this->secret, 
            'algorithm' => $this->algorithm,
            'digits' => $this->digits
        );

        $parameters = array_merge($parameters, $this->format->getParameters());

        if ($this->issuer) {
            $this->accountName = $this->issuer . ':' . $this->accountName;
            $parameters['issuer'] = $this->issuer;
        }

        $this->constructedURL = (
            'otpauth://' . $this->format->getType() .
            '/' . $this->accountName .
            '?' . \http_build_query($parameters)
        );
        
        return $this;
    }

    /**
     * Get the constructed URL.
     * 
     * @return string
     */
    public function get()
    {
        return $this->googleChartURL() . $this->constructedURL;
    }

    /**
     * Google Chart URL.
     * 
     * @return string
     */
    private function googleChartURL()
    {
        return 'https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=';
    }
}
