<?php

class Builder_Helper_Validation extends Builder_Helper_Object
{   
    public function __construct(array $aParam = array())
    {
        if (!empty($aParam['pattern']))
        {
            $sType = !empty($aParam['type']) ? $aParam['type'] : '';
            $this->SetPattern($aParam['pattern'], $sType);
        }

        if (!empty($aParam['required']))
        {
            $this->SetRequired(true);
        }

        if (!empty($aParam['length']['min']))
        {
            $iMin = $aParam['length']['min'];
            $iMax = !empty($aParam['length']['max'])
                        ? $aParam['length']['max']
                        : 0;

            $this->SetMinMax($iMin, $iMax);
        }
        
        if (!empty($aParam['match']))
        {
            $this->SetMatch($aParam['match']);
        }
    }
    
    public function SetPattern($sPattern, $sType = '')
    {
        /*$aAllowedMatches = array(
            'alphanumeric',
            'alpha',
            'numeric',
            'date',
            'email',
            'telephone',
            'url',
            'ajax'
        );

        if (in_array($sPattern, $aAllowedMatches))
        {
            if ($sPattern == 'ajax')
            {
                if ($sType != '')
                {
                    $this->aProperties['type'] = $sType;
                }
                else
                {
                    return $this;
                }
            }*/
            $this->aProperties['pattern'] = $sPattern;
        //}
        return $this;
    }
    
    public function SetMatch($sMatch)
    {
        $this->aProperties['match'] = $sMatch;
        return $this;
    }

    public function SetRequired($bRequired = true)
    {
        $this->aProperties['required'] = $bRequired;
        return $this;
    }

    public function SetMinMax($iMin, $iMax = 0)
    {
        $this->aProperties['length']['min'] = $iMin;
        $iMax
            && $this->aProperties['length']['max'] = $iMax;
        return $this;
    }
}
