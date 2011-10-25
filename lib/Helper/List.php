<?php

class Builder_Helper_List extends Builder_Helper_Object
{
    public function __construct()
    {
    }

    public function ImportListItems(array $aItems)
    {
        foreach ($aItems as $aItem)
        {
            if (!isset($aItem['value']))
            {
                throw new Exception('List item does not contain a value');
            }
            $oItem = new Builder_Helper_List_Item($aItem['value']);
            isset($aItem['name'])
                && $oItem->SetName($aItem['name']);
            isset($aItem['image'])
                && $oItem->SetImage($aItem['image']);

            $this->AddListItem($oItem);
        }
        return $this;
    }

    public function AddListItem(Builder_Helper_List_Item $oItem)
    {
        $this->aProperties[] = $oItem;
        return $this;
    }
}
