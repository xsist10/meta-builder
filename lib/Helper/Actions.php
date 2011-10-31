<?php

class Builder_Helper_Actions extends Builder_Helper_Object
{
    public function __construct()
    {
    }

    public function AddAction(Builder_Helper_Action $oAction)
    {
        $this->aProperties[] = $oAction;
        return $this;
    }
}
