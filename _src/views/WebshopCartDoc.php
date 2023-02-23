<?php
require_once "WebshopDoc.php";
class WebshopCartDoc extends WebshopDoc
{
//==============================================================================
    protected function showMainContent() 
    {
        parent::showMainContent();
        $cart = Tools::getValueFromArray('cart',$this->response,[]);
        echo '<table class="order"><tr><th>Item</th><th>Aantal</th><th>Prijs</th><th>Totaal</th><th>&nbsp;</th></tr>';
        $ordertotal = 0;
        foreach ($cart as $item)
        {
            $itemtotal = $item['price']*$item['amount'];
            $ordertotal += $itemtotal;
            echo '<tr id=row-'.$item['id'].'><td>'
                .$item['productname']
                .'</td><td class="num">'
                .$item['amount']
                .'</td><td class="num">'
                .Tools::nicePrice($item['price'])    
                .'</td><td class="num">'
                .Tools::nicePrice($itemtotal)    
                .'</td><td>'
                .'<div id="removefromcart" title="Verwijder uit winkelmand" data-kvr-item-id="'.$item['id'].'"'
                .'data-kvr-item-amount="'.$item['amount'].'">&#x1F5D1;</div>'    
                . '</tr>';
        }
        echo '<tr><th>&nbsp;</th><th colspan="2">Totaal</th><th id="total_price" class="num">'
            .Tools::nicePrice($ordertotal)
            .'</th><th>&nbsp;</th></tr></table><br/>'
            .'<a class="button" href="'.LINKBASE.'order">Plaats bestelling</a>';
        
    }    
//==============================================================================
}
