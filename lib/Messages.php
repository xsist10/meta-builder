<?php

/*
 * Messages
 *
 * Copyright(c) 2010, Thomas Shone
 * Licensed under the Creative Commons Attribution 3.0 Unported License.
 * http://www.shone.co.za
 *
 * User warning messages
 * To be used in a later version of the system
 */


class Messages
{
    protected static $aOldMessages;
    protected static $aNewMessages;

    public static function HasErrors()
    {
        return !empty(self::$aNewMessages);
    }

    public static function AddError($sError)
    {
        self::$aNewMessages[MESSAGE_ERROR][] = str_replace("\n", "<br/>", $sError);
    }

    public static function AddInlineError($sKey, $sError)
    {
        self::$aNewMessages[MESSAGE_ERROR_INLINE][$sKey][] = str_replace("\n", "<br/>", $sError);
    }

    public static function AddWarning($sWarning)
    {
        self::$aNewMessages[MESSAGE_WARNING][] = str_replace("\n", "<br/>", $sWarning);
    }

    public static function AddNotice($sNotice)
    {
        self::$aNewMessages[MESSAGE_NOTICE][] = str_replace("\n", "<br/>", $sNotice);
    }

    public static function GetInlineErrors($sKey)
    {
        return (
            !empty(self::$aOldMessages[MESSAGE_ERROR_INLINE][$sKey]) ?
                self::$aOldMessages[MESSAGE_ERROR_INLINE][$sKey] :
                ''
        );
    }

    public static function GetMessages()
    {
        // If we don't have any messages, skip the intersect
        $aMessages = self::$aOldMessages;
        if (empty($aMessages))
        {
            $aMessages = self::$aNewMessages;
        }
        if (empty($aMessages))
        {
            return array();
        }

        // Grab all the messages set
        $aTypes = array(
            MESSAGE_ERROR   => true,
            MESSAGE_WARNING => true,
            MESSAGE_NOTICE  => true
        );
        return array_intersect_key($aMessages, $aTypes);
    }

    public static function LoadFromSession()
    {
        $aTypes = array(MESSAGE_ERROR, MESSAGE_WARNING, MESSAGE_NOTICE, MESSAGE_ERROR_INLINE);
        foreach ($aTypes as $sType)
        {
            // Grab messages from session
            $aMessages = Session::Get('Messages.' . $sType);
            if (!empty($aMessages))
            {
                self::$aOldMessages[$sType] = $aMessages;
                // Clear out session entry
                Session::Clear('Messages.' . $sType);
            }
        }
    }

    public static function StoreInSession()
    {
        // Stash messages in the session
        if (!empty(self::$aNewMessages))
        {
            foreach (self::$aNewMessages as $sType => $aMessages)
            {
                Session::Set('Messages.' . $sType, $aMessages);
            }
        }
    }
}