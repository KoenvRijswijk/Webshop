<?php
require_once SRC.'ajax/xml.class.php';
class xml_getWebshopItemByID extends XMLHandler
{
protected $id;
//==============================================================================
    public function getData() 
    {
        $id = Tools::_getVar('id', false, NOP);
        return $this->_crud->selectOne(
            "SELECT * FROM products WHERE id=:id",
            ['id' => [$id, true]]
        );
    }
//==============================================================================
}