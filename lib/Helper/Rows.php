<?php

class Builder_Helper_Rows extends Builder_Helper_Object
{
    public function __construct()
    {
    }

    public function AddRow(Builder_Helper_Row $oRow)
    {
        $this->aProperties[] = $oRow;
        return $this;
    }
}
