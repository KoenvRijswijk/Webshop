<?php
require_once "BaseDAO.php";
class ShopDAO extends BaseDAO
{
//==============================================================================
    public function getWebshopItemByID(int $id) : array|false
    {
        return $this->_crud->selectOne(
            "SELECT products.id, productname, description, image, price, stock, active, count(product_rating.id) as nr_votes, round(avg(product_rating.rating),1) as avg_rate FROM `products` JOIN product_rating ON products.id = product_rating.product_id where products.id =:id",
            ['id' => [$id, true]]
        );
    }
//==============================================================================
    public function getWebshopItems(int $start, int $max_per_page) : array|false
    {
        return $this->_crud->selectMore(
            sprintf("SELECT * FROM products WHERE active = 1 LIMIT %d,%d", $start, $max_per_page),
            []
        );        
    }
//==============================================================================
    public function getAllWebshopItems(int $start = 0, int $max_per_page = 100) : array|false
    {
        return $this->_crud->selectMore(
            sprintf("SELECT * FROM products ORDER BY active  DESC LIMIT %d,%d", $start, $max_per_page),
            []
        );        
    }
//==============================================================================
    public function getWebshopItemsCount(int $start = 0, int $max_per_page = 21) : array|false
    {
        return $this->_crud->selectMore(
            sprintf("SELECT COUNT(*) FROM products WHERE active = 1"),
            []
        );        
    }
//==============================================================================
    public function getAllWebshopItemsCount(int $start = 0, int $max_per_page = 100) : array|false
    {
        return $this->_crud->selectMore(
            sprintf("SELECT COUNT(*) FROM products"),
            []
        );        
    }
//==============================================================================

    public function getWebshopOrders(int $start = 0, int $max_per_page = 21) : array|false
    {
        return $this->_crud->selectMore(
            sprintf("SELECT orders.order_id, orders.status, orders.order_date, orders.user_id, products.productname, products.price, ordered_products.product_id, ordered_products.amount FROM orders INNER JOIN ordered_products ON orders.order_id = ordered_products.order_id INNER JOIN products ON ordered_products.product_id = products.id"),
            []
        );        
    }
//==============================================================================
    public function getWebshopOrderLinesByID(int $id) : array|false
    {
        return $this->_crud->selectMore(
            sprintf("SELECT orders.order_id, orders.status, orders.order_date, orders.user_id, products.productname, products.price, ordered_products.product_id, ordered_products.amount FROM orders INNER JOIN ordered_products ON orders.order_id = ordered_products.order_id INNER JOIN products ON ordered_products.product_id = products.id WHERE orders.order_id=:id"),
            ['id' => [$id, true]]
        );        
    }
//==============================================================================   
    public function getWebshopOrderByID(int $id) : array|false
    {
        return $this->_crud->selectOne(
            sprintf("SELECT * FROM orders WHERE order_id =:id"),
             ['id' => [$id, true]]
        );        
    }
//==============================================================================
    public function editItem(array $item) : int|false 
    {
        if($item['active'] == 'false') {$item['active'] = '0';}
        if($item['id'] > 0)
        
        {
        return $this->_crud->doUpdate(
            "UPDATE products SET productname =:productname, description=:description, image=:image, price=:price, stock=:stock, active=:active WHERE id =:id",
            [
                'productname' => [$item['productname'], false], 
                'description' => [$item['description'], false], 
                'image'  => [$item['image'], false],
                'price'  => [$item['price'], false],
                'stock'  => [$item['stock'], false],
                'active'  => [$item['active'], false],
                'id'     => [$item['id'], false]
            ]    
        );
        }
        else
        {
            $item['id'] = $item['id'] * -1;
            return $this->_crud->doUpdate(
                                "UPDATE products SET active=:active WHERE id =:id",
                [
                    'active'  => [$item['active'], false],
                    'id'     => [$item['id'], false]
                ]
            );      
        }    
    }

 //==============================================================================
    public function insertItem(array $item) : int|false
        {
            //Tools::dump($item);
            return $this->_crud->doInsert(
                "INSERT INTO products (productname, description, image, price, stock) VALUES (:productname, :description, :image, :price, :stock)",
                [
                    'productname' => [$item['productname'], false], 
                    'description' => [$item['description'], false], 
                    'image'  => [$item['image'], false],
                    'price'  => [$item['price'], false],
                    'stock'  => [$item['stock'], false]
                ]    
            );
        }

 //==============================================================================
    public function editOrderStatus(array $order) : int|false 
    {
        return $this->_crud->doUpdate(
                                "UPDATE orders SET status=:status, order_date=:order_date WHERE order_id =:id",
                [
                    'status'  => [$order['status'], false],
                    'order_date'  => [$order['order_date'], false],
                    'id'     => [$order['order_id'], false]
                ]
            );      
    }





}
