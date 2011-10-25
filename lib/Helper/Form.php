<?php

class Builder_Helper_Form extends Builder_Helper_Object
{
    public function __construct($sIdentity)
    {
        $this->aProperties['identity'] = $sIdentity;
        $this->aAllowedProperties = array('identity', 'workflow', 'script', 'groups', 'copy', 'action');
    }

    public function AddGroups(Builder_Helper_Groups $oGroups)
    {
        $this->SetGroups($oGroups);
        return $this;
    }
}