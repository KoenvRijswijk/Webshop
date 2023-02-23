<?php
require_once "WebshopDoc.php";
require_once "WebshopItem.php";
class WebShopDetailDoc extends WebshopDoc
{
//==============================================================================
    protected function showMainContent() 
    {
        parent::showMainContent();
        $item = new WebshopItem(
            Tools::getValueFromArray('item',$this->response,[]),true,    
            Tools::getValueFromArray('loggeduser',$this->response,false)
        );
        $item->show();
        
    }
//==============================================================================
}
