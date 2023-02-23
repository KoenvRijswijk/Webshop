<?php
require_once SRC.'/dal/CartDAO.php';
require_once "WebshopModel.php";
require_once SRC.'/dal/AjaxDAO.php';
class CartModel extends WebshopModel
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
    public function handleViewCart(&$response) : HtmlDoc
    {
        $cart = Tools::getValueFromArray(CART, $_SESSION, []);
        if (count($cart)===0)
        {
            $response[SYSERR] = 'Geen items.';
        } 
        else
        {
            $response['cart'] = $this->cartdao->getCartItems($cart);
        }    
        return $this->createWebshopCartDoc($response);        
    }        
//==============================================================================
    public function handleAddToCart(&$response) : HtmlDoc
    {
        $item = $this->getItemByUrlParameter();
        if ($item === false)
        {
            $response[SYSERR] = 'Item niet gevonden.';
        }   
        else
        {
            $amount = Tools::getRequestVar('amount',false,1,true);
            $response['page'] = Tools::getRequestVar('caller',false,'webshop',false);
            if ($this->addToCart($item['id'], $amount))
            {
                $response[SYSMSG] = 'Item toegevoegd aan winkelwagen.';
            }
            else
            {
                $response[ERRMSG] = 'Kan item niet toevoegen aan winkelwagen.';
            }
        }
        if ($response['page']==='detail')
        {
            $response['item'] = $item;
            return $this->createWebShopDetailDoc($response);
        }    
        else
        {
            $response['page'] = 'webshop';
            return $this->handleWebshopItems($response);
        }
    }           
//==============================================================================
    public function handleRemoveFromCart(&$response) : HtmlDoc
    {
        $item = $this->getItemByUrlParameter();
        if ($item === false)
        {
            $response[SYSERR] = 'Item niet gevonden.';
        }   
        if ($this->removeFromCart($item['id']))
        {
            $response['page'] = $this->hasCart()?'cart':'webshop';
        }
        else
        {
            $response['page'] = 'webshop';
        }
        if ($response['page']==='cart')
        {
            return $this->handleViewCart($response);
        }
        else
        {
            return $this->handleWebshopItems($response);
        }
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
