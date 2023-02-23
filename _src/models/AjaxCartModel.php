<?php
require_once SRC.'/dal/CartDAO.php';
require_once "WebshopModel.php";
require_once SRC.'/dal/AjaxDAO.php';
class AjaxCartModel extends WebshopModel
{
    protected CartDAO $cartdao;
//==============================================================================
    public function __construct()
    {
        parent::__construct();
        $this->cartdao = new CartDAO();
        $this->ajaxdao = new AjaxDAO();
    }

//==============================================================================
    public function checkcartcount()
        {
            if(!empty($_SESSION['CART']))
            {
                $counter = array_sum($_SESSION['CART']);
            } 
            else 
            {
                $counter = 0;
            }
            return $counter;
            
        }
//==============================================================================
    public function handleAddToCart() : array
    {
        $item = $this->getItemByUrlParameter('itemID');
        $response=array();
        $amount = $_GET['amount'];
        if ($item === false)
        {
            $response['MSG'] = 'Item niet gevonden.';
        }   
        else
        {
            if ($this->addToCart($item['id'], $amount))
            {
                $response['MSG'] = 'Item toegevoegd aan winkelwagen.';
            }
            else
            {
                $response['MSG'] = 'Kan item niet toevoegen aan winkelwagen.';
            }
        }
        $response['amount'] = $_GET['amount'];
        $response['counter'] = intval($_GET['current_value']) + $response['amount'];
        return $response;
    }           
//==============================================================================
    public function handleRemoveFromCart() : array
    {
        // 2 nummers ontvangen
        $item = $this->getItemByUrlParameter('itemID');
        $resonse = array();
        $response['amount'] = $_SESSION['CART'][$item['id']];
        if ($item === false)
        {
            $response['MSG'] = 'Item niet gevonden.';
        }   
        if ($this->removeFromCart($item['id']))
        {
            $response['MSG'] = 'Item verwijderd uit winkelwagen.';
        }
        $response['total_cart'] = $this->calculateCartTotal();
        $response['counter'] = intval($_GET['current_value']) - $response['amount'];
        return $response;
    }        
//==============================================================================
    public function handleSaveOrder(&$response) : HtmlDoc
    {
        if ($this->hasCart())
        {    
            $order = $this->cartdao->saveOrder($_SESSION[USERID], $_SESSION[CART]);
        }
        else
        {
            $response['page']= 'home';
            return $this->createWebShopDoc($response);
        }
        if ($order)
        {
            unset($_SESSION[CART]);
            $response['page'] = 'webshop';
            $response[SYSMSG] = 'Bestelling bewaard onder nummer ['.sprintf("%'.09d",$order).']';
            return $this->handleWebshopItems($response);
        }  
        else
        {
            $response['page'] = 'cart';
            $response[SYSERR] = 'Bestelling kon niet worden bewaard.';
            return $this->handleViewCart($response);
        }
    } 
//==============================================================================

   protected function calculateCartTotal() : mixed
   {
    
    $cart = Tools::getValueFromArray(CART, $_SESSION, []);
    $itemtotal=0;
        if (count($cart)===0)
        {
            $response[SYSERR] = 'Geen items.';
        } 
        else
        {
            $response['cart'] = $this->cartdao->getCartItems($cart);
            $itemtotal=0;
            foreach($response['cart'] as $item)
            {
                $itemtotal += $item['amount'] * $item['price'];
            }
        }  
        return Tools::nicePrice($itemtotal);
   }
//==============================================================================
    protected function createWebshopCartDoc(&$response) : HtmlDoc
    {
        $this->updateResponse($response);
        require_once SRC.'views/WebShopCartDoc.php';
        return new WebShopCartDoc($response);
    }        
    
//==============================================================================
    private function getItemByUrlParameter() : array|false
    {
        $id = Tools::getRequestVar('item',false,0,true);
        if ($id > 0)
        {
            $item = $this->cartdao->getWebshopItemByID($id);
            $item += $this->ajaxdao->getCountRatingByItem($id);
            $item += $this->ajaxdao->getProductAVGRating($id);

            return $item;
        }
        return false;
    }        
//==============================================================================
    private function addToCart(int $item, int $amount) : bool
    {
        if (isset($_SESSION[CART])===false)
        {
            $_SESSION[CART] = [];
        }
        return $this->upDateCart($_SESSION[CART], $item, $amount, true);
    }
//==============================================================================
    private function removeFromCart(int $item) : bool
    {
        return $this->upDateCart($_SESSION[CART], $item, 0, false);
    }
//==============================================================================
    private function changeAmount(int $item, int $amount) : bool
    {
        return $this->upDateCart($_SESSION[CART], $item, $amount, false);
    }
//==============================================================================
    private function upDateCart(array &$cart, int $item, int $amount, bool $add) : bool
    {
        if (array_key_exists($item, $cart))
        {
            $add ? $cart[$item] += $amount
                 :  $cart[$item] = $amount; 
        }
        else
        {
            $cart[$item] = $amount; 
        }
        if ($cart[$item] === 0) 
        {
            unset($cart[$item]);
        }
        return true;
    }
//==============================================================================
    
}
