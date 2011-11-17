<?php

class Builder_Helper_Element extends Builder_Helper_Object
{
    public function __construct($sName, $sType = Builder_Form_Element::TYPE_TEXT)
    {
        $this->aProperties['name'] = $sName;
        $this->aProperties['type'] = $sType;

        $this->aAllowedProperties = array('description', 'name', 'value', 'size', 'hint', 'list', 'tooltip', 'autocomplete', 'mode', 'rows', 'cols', 'error');
    }

    public function SetValidation(Builder_Helper_Validation $oValidation)
    {
        $this->aProperties['validation'] = $oValidation;
        return $this;
    }

    public function SetType($sType)
    {
        if ($this->aProperties['type'] != 'list')
        {
            unset($this->aProperties['render-mode']);
        }
        return $this;
    }

    public function SetRenderMode($sRenderMode)
    {
        if ($this->aProperties['type'] == 'list')
        {
            $this->aProperties['render-mode'] = $sRenderMode;
        }
        return $this;
    }
}
