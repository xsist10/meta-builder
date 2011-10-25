<?php

class Builder_Helper_Object
{
    protected $aProperties;
    protected $aAllowedProperties = array();

    private function ___SetProperties(array $aProperties)
    {
        $this->aProperties = $aProperties;
    }

    public function ToArray()
    {
        return $this->___ConvertToArray($this->aProperties);
    }

    protected function ___ConvertToArray($mData)
    {
        if (is_object($mData))
        {
            return $mData->ToArray();
        }
        else if (is_array($mData))
        {
            $aResult = array();
            foreach ($mData as $sKey => $mContent)
            {
                $aResult[$sKey] = $this->___ConvertToArray($mContent);
            }
            return $aResult;
        }
        else
        {
            return $mData;
        }
    }

    public function __get($sName)
    {
        $sName = strtolower($sName);
        if (!empty($this->aProperties[$sName]))
        {
            return $this->aProperties[$sName];
        }
        throw new Exception('Attempting to access unknown property: ' . $sName);
    }

    public function __call($sName,  $aParam)
    {
        if (strpos($sName, 'Set') === false)
        {
            return $this;
        }
        $mValue = !empty($aParam[0])
                    ? $aParam[0]
                    : '';
        $sAttribute = strtolower(substr($sName, 3));
        if (in_array($sAttribute, $this->aAllowedProperties))
        {
            //$this->aProperties[$sAttribute] = $this->___ConvertToArray($mValue);
            $this->aProperties[$sAttribute] = $mValue;
        }
        else
        {
            throw new Exception('Attempting to set invalid field "' . $sAttribute .'" on ' . get_class($this));
        }
        return $this;
    }
}
