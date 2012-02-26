<?php

class Builder_Helper_Column extends Builder_Helper_Object
{
    public function __construct()
    {
        $this->aAllowedProperties = array('name', 'type', 'label', 'sort', 'format', 'link');
    }

    public function setFormat($sFunction, $mArguments = array())
    {
    	$sFormat = $sFunction;
    	if (!empty($mArguments))
    	{
    		if (is_array($mArguments))
    		{
    			$sFormat .= '|' . implode(',', $mArguments);
    		}
    		else
    		{
    			$sFormat .= '|' . $mArguments;
    		}
    	}
    	$this->aProperties['format'] = $sFormat;
        return $this;
    }
}
