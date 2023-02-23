<?php
require_once SRC.'ajax/json.class.php';
class JSON_AddToCart extends JSONHandler
{
//==============================================================================
    public function getData()
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
    private function addToCart(int $item, int $amount) : bool
    {
        if (isset($_SESSION[CART])===false)
        {
            $_SESSION[CART] = [];
        }
        return $this->upDateCart($_SESSION[CART], $item, $amount, true);
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
    private function getWebshopItemByID(int $id) : array|false
    {
        return $this->_crud->selectOne(
            "SELECT products.id, productname, description, image, price, stock, active, count(product_rating.id) as nr_votes, avg(product_rating.rating) as avg_rate FROM `products` JOIN product_rating ON products.id = product_rating.product_id where products.id =:id",
            ['id' => [$id, true]]
        );
    }
}