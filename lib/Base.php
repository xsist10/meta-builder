<?php

/*
 * BuilderBase
 *
 * Copyright(c) 2010, Thomas Shone
 * Licensed under the Creative Commons Attribution 3.0 Unported License.
 * http://www.shone.co.za
 *
 * Base class of Builder
 */

class Builder_Base
{
    protected $sResult;
    protected $aData;
    protected $aMeta;

    protected function BuildComplexKey($mData, $sKey, $sFunction)
    {
        if (strpos($sFunction, '|') !== false)
        {
            $aFuction = explode('|', $sFunction);
            $sAction = $aFuction[0];
            $aParams = explode(',', $aFuction[1]);

            $sResult = "";

            switch ($sAction)
            {
                case 'concat':
                    foreach ($aParams as $sParam)
                    {
                        if (isset($mData[$sParam]))
                        {
                            $sResult .= $mData[$sParam];
                        }
                        else
                        {
                            $sResult .= $sParam;
                        }
                    }
                    break;

                case 'truncate':
                    $sTruncateLength = !empty($aParams[0]) ? $aParams[0] : 40;
                    $sEnding = '';
                    if (!empty($aParams[1]))
                    {
                        $sEnding = $aParams[1];
                        $sTruncateLength -= strlen($sEnding);
                    }

                    if ($sTruncateLength <= strlen($mData[$sKey]))
                    {
                        $sResult = $mData[$sKey];
                    }
                    else
                    {
                        $sResult = substr($mData[$sKey], 0, $sTruncateLength) . $sEnding;
                    }
                    break;
            }

            return $sResult;
        }
        else
        {
            return $mData;
        }
    }

    protected function ConstructField($mData, $sKey, $sFormat = '')
    {
        // Are we dealing with a Complex Key?
        if (strpos($sFormat, '|') !== false)
        {
            return $this->BuildComplexKey($mData, $sKey, $sFormat);
        }
        else
        {
            switch ($sFormat)
            {
                case 'email':
                    return '<a href="mailto:' . $mData[$sKey]. '">' . $mData[$sKey] . '</a>';
                case 'ip_address':
                    return $mData[$sKey] ? inet_ntoa($mData[$sKey]) : '<i>empty</i>';
                case 'coordinates':
                	if (!empty($mData[$sKey]))
                	{
	                    $aCoords = explode(',', $mData[$sKey]);
	                    if (count($aCoords) == 2)
	                    {
	    	                $sUrl = 'http://maps.google.com/?ll=' . $aCoords[0] .','. $aCoords[1] . '&z=16';
		                    return '<a href="' . $sUrl . '"><img src="' . BuildImage('16x16/map-pin.png') . '" align="left" />&nbsp;' . $aCoords[0] .', '. $aCoords[1] . '</a>';
	                    }
	                    else
	                    {
	                    	return '<i>Invalid Coords</i>';
	                    }
                	}
                	else
                	{
                		return '<i>No Coords</i>';
                	}
                    break;
                case 'relative_time':
                    return $mData[$sKey] ? RelativeTime($mData[$sKey]) : '<i>empty</i>';
                case 'datetime':
                    return $mData[$sKey] ? date('Y-m-d H:i:s', $mData[$sKey]) : '<i>empty</i>';
                case 'date':
                    return $mData[$sKey] ? date('Y-m-d', $mData[$sKey]) : '<i>empty</i>';
                case 'time':
                    return $mData[$sKey] ? date('H:i:s', $mData[$sKey]) : '<i>empty</i>';
                default:
                    if (!is_array($mData))
                    {
                        return !empty($mData) ? $mData : '&nbsp;';
                    }
                    else
                    {
                        return !empty($mData[$sKey]) ? $mData[$sKey] : '&nbsp;';
                    }
            }
        }
    }

    protected function Build($aMeta)
    {
        if (!empty($aMeta) && is_array($aMeta))
        {
            foreach ($aMeta as $sKey => $aContent)
            {
                $sFunction = 'Build' . ucwords($sKey);
                if (method_exists($this, $sFunction))
                {
                    $this->$sFunction($aContent);
                }
            }
        }
    }

    protected function SetConfig($aMeta, $aData = array())
    {
        $this->aMeta = $aMeta;
        if (!empty($aData))
        {
            $this->aData = $aData;
        }
    }

/*    protected function BuildScript($sScript)
    {
        $this->sResult .= '<script src="' . BuildUrl('html/javascript/'. $sScript) .'" type="text/javascript"></script>' . "\n";
    }*/
    
    public function RenderMessages($aMessages)
    {
        foreach ($aMessages as $sType => $aTypeMessages)
        {
            echo '<ul class="messages messages-' . strtolower($sType) . '">';
            foreach ($aTypeMessages as $sMessage)
            {
                echo '<li>' . $sMessage . '</li>';
            }
            echo '</ul>';
        }
    }   

    public function Render($aMeta, $aData = array())
    {
        $this->SetConfig($aMeta, $aData);
        $this->sResult = '';
        $this->Build($aMeta);
        return $this->sResult;
    }
}