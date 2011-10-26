<?php

class Builder_Helper_Column extends Builder_Helper_Object
{
    public function __construct()
    {
        $this->aAllowedProperties = array('name', 'type', 'label', 'sort', 'format', 'link');
    }
}
