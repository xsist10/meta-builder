<?php

class Builder_Helper_Columns extends Builder_Helper_Object
{
    public function __construct()
    {
    }

    public function AddColumn(Builder_Helper_Column $oColumn)
    {
        $this->aProperties[] = $oColumn;
        return $this;
    }
}
