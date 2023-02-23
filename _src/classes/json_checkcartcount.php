<?php
require_once SRC.'ajax/json.class.php';
class JSON_CheckCartCount extends JSONHandler
{
//==============================================================================
public function getData()
        {
            if(!empty($_SESSION['CART']))
            {
                $counter = ['counter' => array_sum($_SESSION['CART'])];
            } 
            else 
            {
                $counter = ['counter' => 0];
            }
        return $counter;    
        }
//==============================================================================
}