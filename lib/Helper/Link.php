<?php

class Builder_Helper_Link extends Builder_Helper_Object
{
    public function __construct()
    {
        $this->aAllowedProperties = array('path', 'param', 'tooltip');
    }
}
