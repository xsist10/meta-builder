<?php

class Builder_Helper_Row extends Builder_Helper_Object
{
    public function __construct($sLabel = '', $sImage = '')
    {
        $this->aProperties['label'] = $sLabel;
        $sImage
            && $this->aProperties['image'] = $sImage;

        $this->aAllowedProperties = array('label', 'image');
    }

    public function AddElement(Builder_Helper_Element $oElement)
    {
        $this->aProperties['element'][] = $oElement;
        return $this;
    }
}
