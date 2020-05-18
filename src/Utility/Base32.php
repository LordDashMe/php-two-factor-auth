<?php

/*
 * This file is part of the  Two Factor Auth.
 *
 * (c) Joshua Clifford Reyes <reyesjoshuaclifford@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this surce code.
 */

namespace LordDashMe\TwoFactorAuth\Utility;

/**
 * Base32 Utility.
 * 
 * @author Joshua Clifford Reyes <reyesjoshuaclifford@gmail.com>
 */
class Base32
{
    /**
     * Encode the given content.
     * 
     * @param  string  $content  The content that will be converted to base32 string.
     * @param  boolean $padding  Add padding on the base32 string generated.
     * 
     * @return string
     */
    public static function encode($content, $padding = true)
    {
        $binary = '';
        $encoded = '';
        
        $map = \array_merge(\range('A', 'Z'), \range(2, 7));
        $chars = \str_split($content);
        $charsLimit = \count($chars);
        
        for ($i = 0; $i < $charsLimit; $i++) {
            $binary .= \str_pad(\decbin(\ord($chars[$i])), 8, 0, STR_PAD_LEFT);
        }
        
        $groups = \str_split($binary, 5);
        $groupsLimit = \count($groups) - 1;
        
        for ($i = 0; $i < $groupsLimit; $i++) {
            $encoded .= $map[\bindec($groups[$i])];
        }
        
        $encoded .= $map[\bindec(\str_pad($groups[$groupsLimit], 5, 0))];
        $encoded .= $padding ? \str_repeat('=', 7 - (\strlen($encoded) - 1) % 8) : '';
        
        return $encoded;
    }

    /**
     * Decode the given base32 content.
     * 
     * @param  string  $content  The content that will be decode from base32 to plain text.
     * 
     * @return string
     */
    public static function decode($content)
    {
        $binary = '';
        $decoded = '';
        
        $replacements = array(
            '0' => 'O',
            '1' => 'I',
            ' ' => '',
        );
        
        $map = \array_flip(\array_merge(\range('A', 'Z'), \range(2, 7)));
        $normalized = \strtr(\strtoupper(\rtrim($content, '=')), $replacements);
        
        $chars = \str_split($normalized);
        $charsCount = \count($chars);
        
        for ($i = 0; $i < $charsCount; $i++) {
            $binary .= \str_pad(\decbin($map[$chars[$i]]), 5, 0, STR_PAD_LEFT);
        }
        
        $groups = \str_split(\substr($binary, 0, \strlen($binary) - \strlen($binary) % 8), 8);
        $groupsLimit = \count($groups);
        
        for ($i = 0; $i < $groupsLimit; $i++) {
            $decoded .= \chr(\bindec($groups[$i]));
        }

        return $decoded;
    }
}
