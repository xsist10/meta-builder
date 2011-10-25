<?php

class Builder_Helper_Groups extends Builder_Helper_Object
{
    public function __construct()
    {
    }

    public function AddGroup(Builder_Helper_Group $oGroup)
    {
        $this->aProperties[] = $oGroup;
        return $this;
    }
}
