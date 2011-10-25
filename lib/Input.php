<?php

/*
 * Input
 *
 * Copyright(c) 2010, Thomas Shone
 * Licensed under the Creative Commons Attribution 3.0 Unported License.
 * http://www.shone.co.za
 *
 * Manages use input
 * To be used in a later version of the system
 */

class Input
{
    protected static $aInput;

    private static function Sanitize($mValue)
    {
        if (is_array($mValue))
        {
            $aResult = array();
            foreach ($mValue as $sKey => $mChildValue)
            {
                $aResult[$sKey] = self::Sanitize($mChildValue);
            }
            return $aResult;
        }
        else
        {
            return trim(strip_tags($mValue));
        }
    }

    public static function LoadUserInputs()
    {
        self::$aInput = self::Sanitize($_REQUEST);
        //Debug::Log(self::$aInput, 'User Input');
    }

    public static function Get($sKey = '')
    {
        if (!$sKey)
        {
            return self::$aInput;
        }
        return (!empty(self::$aInput[$sKey]) ? self::$aInput[$sKey] : '');
    }

    public static function GetPost($sKey = '')
    {
        if ($sKey)
        {
            return !empty(self::$aInput[INPUT][$sKey]) ? self::$aInput[INPUT][$sKey] : '';
        }
        else if (!empty(self::$aInput[INPUT]))
        {
            return self::$aInput[INPUT];
        }
        else
        {
            return array();
        }
    }
    
    public static function ValidateCaptcha()
    {
    	$sChallenge = Input::Get('recaptcha_challenge_field');
    	$sResponseField = Input::Get('recaptcha_response_field');
    	$sPrivatekey = CAPTCHA_PRIVATE_KEY;
    	
    	require_once('3rd/reCAPTCHA/recaptchalib.php');
    	$oResponse = recaptcha_check_answer($sPrivatekey,
									    	$_SERVER["REMOTE_ADDR"],
									    	$sChallenge,
									    	$sResponseField
    										);
    	
		return $oResponse->is_valid;
    }
}

Input::LoadUserInputs();