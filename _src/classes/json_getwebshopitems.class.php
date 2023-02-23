<?php
require_once SRC.'ajax/json.class.php';
class json_getWebshopItems extends JSONHandler
{
//==============================================================================
    public function getData()
    {
        return $this->_crud->selectMore(
            sprintf("SELECT * FROM products WHERE active = 1"),
            []
        );        
    }
//==============================================================================
}