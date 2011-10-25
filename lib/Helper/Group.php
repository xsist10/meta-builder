<?php

class Builder_Helper_Group extends Builder_Helper_Object
{
    public function __construct()
    {
        $this->aAllowedProperties = array('heading', 'subheading', 'copy', 'rows');
    }
}
