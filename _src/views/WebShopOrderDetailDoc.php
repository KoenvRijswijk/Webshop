<?php
require_once "WebshopDoc.php";
require_once "WebshopOrder.php";
class WebShopOrderDetailDoc extends WebshopDoc
{
//==============================================================================
    protected function showMainContent() 
    {
        parent::showMainContent();
        $order = new WebshopOrder(
            Tools::getValueFromArray('order',$this->response,[]), true,
            Tools::getValueFromArray('loggeduser',$this->response,false,

        ));
        $order->show();
        
    }
//==============================================================================
}
