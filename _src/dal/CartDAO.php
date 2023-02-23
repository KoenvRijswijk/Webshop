<?php
require_once "ShopDAO.php";
class CartDAO extends ShopDAO
{
//==============================================================================
    public function getCartItems(array $cart) : array|false
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
    public function saveOrder(int $user_id, array $cart) : int|false
    {
        try
        {
            $this->_crud->beginTransaction();
            $order_id = $this->_crud->doInsert(
                    "INSERT INTO orders (user_id) VALUES (:user_id)",
                    ['user_id'=>[$user_id, true]]
            );
            if ($order_id)
            {
                foreach ($cart as $product_id => $amount)
                {
                    if ($this->_crud->doInsert(
                        "INSERT INTO ordered_products (order_id, product_id, amount)"
                        ." VALUES (:order_id, :product_id, :amount)",
                        [
                            'order_id'=>[$order_id, true],
                            'product_id'=>[$product_id, true],
                            'amount'=>[$amount, true]
                        ])===false)
                    {        
                        throw new Exception($this->_crud->getLastError());
                    }    
                }    
            } 
            $this->_crud->commit();
            return $order_id;
        }
        catch (Exception $e)
        {
            $this->_setLastError($e->getMessage());
            $this->_crud->rollBack();
            return false;
        }
    }    
//==============================================================================
  
}
