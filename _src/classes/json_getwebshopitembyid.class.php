<?php
require_once SRC.'ajax/json.class.php';
class json_getWebshopItemByID extends JSONHandler
{
protected $id;
//==============================================================================
    public function getData() 
    {
        $id = Tools::_getVar('id', false, NOP);
        return $this->_crud->selectOne(
            "SELECT products.id, productname, description, image, price, stock, active, count(product_rating.id) as nr_votes, avg(product_rating.rating) as avg_rate FROM `products` JOIN product_rating ON products.id = product_rating.product_id where products.id =:id",
            ['id' => [$id, true]]
        );
    }
//==============================================================================
}