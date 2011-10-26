<?php

class Builder_Helper_Actions extends Builder_Helper_Object
{
    public function __construct()
    {
    }

    public function AddColumn(Builder_Helper_Action $oAction)
    {
        $this->aProperties[] = $oAction;
        return $this;
    }
}
