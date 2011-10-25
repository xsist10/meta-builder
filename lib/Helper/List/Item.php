<?php

class Builder_Helper_List_Item extends Builder_Helper_Object
{
    public function __construct($sValue, $sName = '', $sImage = '')
    {
        $this->aProperties['value'] = $sValue;
        $sName
            && $this->aProperties['name'] = $sName;
        $sImage
            && $this->aProperties['image'] = $sImage;

        $this->aAllowedProperties = array('name', 'value', 'image');
    }
}