<?php

class Builder_Helper_Grid extends Builder_Helper_Object
{
    public function __construct($sIdentity)
    {
        $this->aProperties['identity'] = $sIdentity;
        $this->aAllowedProperties = array('identity', 'workflow', 'script', 'heading', 'subheading', 'copy', 'columns', 'paging', 'group');
    }

    public function AddColumns(Builder_Helper_Columns $oColumns)
    {
        $this->SetColumns($oColumns);
        return $this;
    }
    
    public function AddFooterActions(Builder_Helper_Actions $oActions)
    {
    	$this->aProperties['action']['footer'] = $oActions;
    	return $this;
    }
}