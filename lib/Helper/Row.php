<?php

class Builder_Helper_Row extends Builder_Helper_Object
{
    public function __construct($sLabel = '', $sImage = '')
    {
        $this->aProperties['label'] = $sLabel;
        $sImage
            && $this->aProperties['image'] = $sImage;

        $this->aAllowedProperties = array('label', 'image', 'render-mode');
    }

    public function AddElement(Builder_Helper_Element $oElement)
    {
        $this->aProperties['element'][] = $oElement;
        return $this;
    }
    
    public function SetRenderMode($sRenderMode)
    {
		$this->aProperties['render-mode'] = $sRenderMode;
    	return $this;
    }
}
