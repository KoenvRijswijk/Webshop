<?php
require_once SRC.'ajax/json.class.php';
class JSON_RemoveFromCart extends JSONHandler
{
//==============================================================================
    public function getData()
    {
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
  private function getItemByUrlParameter() : array|false
    {
        $id = Tools::getRequestVar('item',false,0,true);
        if ($id > 0)
        {
            $item = $this->getWebshopItemByID($id);
            return $item;
        }
        return false;
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
    private function calculateCartTotal() : mixed
    {
    
    $cart = Tools::getValueFromArray(CART, $_SESSION, []);
    $itemtotal=0;
        if (count($cart)===0)
        {
            $response[SYSERR] = 'Geen items.';
        } 
        else
        {
            $response['cart'] = $this->getCartItems($cart);
            $itemtotal=0;
            foreach($response['cart'] as $item)
            {
                $itemtotal += $item['amount'] * $item['price'];
            }
        }  
        return Tools::nicePrice($itemtotal);
   }
//==============================================================================
    private function getWebshopItemByID(int $id) : array|false
    {
        return $this->_crud->selectOne(
            "SELECT products.id, productname, description, image, price, stock, active, count(product_rating.id) as nr_votes, avg(product_rating.rating) as avg_rate FROM `products` JOIN product_rating ON products.id = product_rating.product_id where products.id =:id",
            ['id' => [$id, true]]
        );
    }
//==============================================================================
    private function getCartItems(array $cart) : array|false
    {
        $ids = implode(',', array_keys($cart));
        $items = $this->_crud->selectMore(
                    "SELECT * FROM products WHERE id in (".$ids.")", []
                );
        if ($items && count($items)>0)
        {    
            for ($i=0; $i<count($items); $i++)
            {
                $items[$i]['amount'] = $cart[$items[$i]['id']];
            }
        }
        return $items;
    }
//==============================================================================
}