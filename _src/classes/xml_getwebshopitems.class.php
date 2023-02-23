<?php
require_once SRC.'ajax/xml.class.php';
class xml_getWebshopItems extends XMLHandler
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