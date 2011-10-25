<?php

/*
 * Sessoin
 *
 * Copyright(c) 2010, Thomas Shone
 * Licensed under the Creative Commons Attribution 3.0 Unported License.
 * http://www.shone.co.za
 *
 * User session management
 * To be used in a later version of the system
 */

class Session
{
    public static function Get($sKey)
    {
        if (!empty($_SESSION[SESSION][$sKey]))
        {
            return $_SESSION[SESSION][$sKey];
        }
        else
        {
            return '';
        }
    }

    public static function GetKey($sSection, $sKey)
    {
    if (!empty($_SESSION[SESSION][$sSection][$sKey]))
        {
            return $_SESSION[SESSION][$sSection][$sKey];
        }
        else
        {
            return null;
        }
    }

    public static function Set($sKey, $mValue)
    {
        $_SESSION[SESSION][$sKey] = $mValue;
    }

    public static function SetInput($sKey, $mValue)
    {
        $_SESSION[SESSION][SESSION_FORM_POST][$sKey] = $mValue;
    }

    public static function GetInput($sKey)
    {
        return self::GetKey(SESSION_FORM_POST, $sKey);
    }

    public static function Clear($sKey)
    {
        unset($_SESSION[SESSION][$sKey]);
    }
}

?>