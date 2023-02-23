<?php
require_once "WebshopDoc.php";
require_once "WebshopItem.php";
class WebshopItemsDoc extends WebshopDoc
{
//==============================================================================
    protected function showMainContent() 
    {
        parent::showMainContent();
        $items = Tools::getValueFromArray('items',$this->response,[]);
        $loggeduser = Tools::getValueFromArray('loggeduser',$this->response,false);
        $total_pages = Tools::getValueFromArray('total_pages',$this->response,false);
        $pageno = Tools::getValueFromArray('pageno',$this->response,false);
        echo '<div class="webshop">'.PHP_EOL;
        foreach ($items as $item)
        {   
            $itemview = new WebshopItem($item, false, $loggeduser);
            $itemview->show();
        } 
        echo '</div>'.PHP_EOL;
        
        echo'
        <ul class="pagination justify-content-center">
        <li class="page-item"><a class="page-link" href="?page=webshop&pageno=1">First</a></li>
        <li class="';
        if($pageno <= 1){ echo 'disabled'; } else {echo 'page-item';}
        echo '"><a class="page-link" href="';
        if($pageno <= 1){ echo '#'; } else { echo "?page=webshop&pageno=".($pageno - 1); } 
        echo '">Prev</a></li><li class="';
        if($pageno >= $total_pages){ echo 'disabled'; } else {echo 'page-item';}
        echo '"><a class="page-link" href="';
        if($pageno >= $total_pages){ echo '#'; } else { echo "?page=webshop&pageno=".($pageno + 1); } 
        echo' ">Next</a></li><li><a class="page-link "href="?page=webshop&pageno='.$total_pages.'
        ">Last</a></li>
    </ul>';
    }    
//==============================================================================
}
