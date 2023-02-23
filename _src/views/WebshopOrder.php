<?php
require_once SRC.'interfaces/iHtmlView.php';
class WebshopOrder implements iHtmlView
{
    protected array $order;
    protected bool $detail;
    protected bool $loggeduser;
//==============================================================================
    public function __construct(array $orderdata, bool $detail, bool $loggeduser)
    {
        $this->order        = $orderdata;
        $this->detail       = $detail;
        $this->loggeduser   = $loggeduser;
    }        
//==============================================================================
// Implementation of iHtmlView interface
//==============================================================================
   
    public function show()
    {
         
         if(!$this->detail)
         {
            echo'  <div class="row"><div class="col-1">'.$this->order['order_id'].'</div>'.PHP_EOL   
                .($this->detail?'<div class="col-2">'.$this->order['user_id'].'</div>':'').PHP_EOL
                .'  <div class="col-2">'.$this->order['date'][0].'</div>'.PHP_EOL
                .'  <div class="col-1">'.$this->order['quantity'].'</div>'.PHP_EOL
                .'  <div class="col-2">'.Tools::nicePrice($this->order['amount']).'</div>'.PHP_EOL
                .'  <div class="col-2">'.$this->order['status'][0].'</div>'.PHP_EOL
                . ''.PHP_EOL; 

            if($_SESSION[USERROLE] > 60)
            {
                echo '<div class="col-2"><a class="button" href="'.LINKBASE.'editorder&order=-'.$this->order['order_id'].'">edit status</a>&nbsp;</div>'.PHP_EOL;
            }     

            if($_SESSION[USERROLE] > 60)
            {
                $extra = $this->detail?'&caller=detail':'';
                echo '<div class="col-2"><a class="button" href="'.LINKBASE.'editorder&order='.$this->order['order_id'].$extra.'">details</a></div>'.PHP_EOL;
            }        
            echo ''.PHP_EOL
                .'</div>'.PHP_EOL;
        }
        else 
        {
            $totaalPrijs = 0;
            echo'<div class="container">'.PHP_EOL;
            echo   '<div class="row py-2 text-uppercase">
                <div class="col-1">Bestel #nr</div>
                <div class="col-2">datum #nr</div>
                <div class="col-4">item naam</div>
                <div class="col-1">#Items</div>
                <div class="col-1">bedrag</div>
                <div class="col-1">totaal bedrag</div>
                <div class="col-2">status</div>
                </div>';
               foreach($this->order as $order_lines)
               {
                echo '<div class="row"><div class="col-1">'.$order_lines['order_id'].'</div>
                         <div class="col-2">'.$order_lines['order_date'].'</div>
                        <div class="col-4">'.$order_lines['productname'].'</div>
                        <div class="col-1">'.$order_lines['amount'].'</div>
                        <div class="col-1">'.Tools::nicePrice($order_lines['price']).'</div>
                        <div class="col-1">'.Tools::nicePrice($order_lines['price']*$order_lines['amount']).'</div>
                        <div class="col-2">'.$order_lines['status'].'</div>';

                $totaalPrijs += $order_lines['price']*$order_lines['amount'];
               } 
                echo '<div class="row"><div class="col-8"></div><div class="col-4 ">  Totaal bestelling:  '.Tools::nicePrice($totaalPrijs) .'</div></div>';

             echo '</div>';   
        }
    }  

        
}

/*SELECT * FROM orders INNER JOIN ordered_products ON orders.order_id = ordered_products.order_id INNER JOIN products ON ordered_products.product_id = products.id WHERE orders.order_id = 55; */
