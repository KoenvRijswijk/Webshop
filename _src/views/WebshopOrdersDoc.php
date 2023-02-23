<?php
require_once "WebshopDoc.php";
require_once "WebshopOrder.php";
class WebshopOrdersDoc extends WebshopDoc
{
//==============================================================================
    protected function showMainContent() 
    {
        parent::showMainContent();
        $orders = Tools::getValueFromArray('orders',$this->response,[]);
        $loggeduser = Tools::getValueFromArray('loggeduser',$this->response,false);
        echo '<div class="container">'.PHP_EOL;
        echo   '<div class="row text-uppercase">
                <div class="col-1">Bestel #nr</div>
                <div class="col-2">datum #nr</div>
                <div class="col-1">#Items</div>
                <div class="col-2">Totaal bedrag</div>
                <div class="col-2">bestel status</div>
                <div class="col-2">verander status</div>
                <div class="col-2">check details</div>
                </div>

                ';
        foreach ($orders as $order)
        {   
            $orderview = new WebshopOrder($order, false, $loggeduser);
            $orderview->show();
        } 
        echo '</table>'.PHP_EOL;
    }    
//==============================================================================
}
