<?php
require_once "WebshopDoc.php";
require_once "HtmlDbForm.php";
class WebshopDbFormDoc extends WebshopDoc
{
//==============================================================================
    protected function showMainContent() 
    {
        parent::showMainContent(); 
        $form = new HtmlDbForm(
            Tools::getValueFromArray('id',         $this->response,[]),
            Tools::getValueFromArray('item',       $this->response,[]),
            Tools::getValueFromArray('forminfo',   $this->response,[]),    
            Tools::getValueFromArray('fieldinfo',  $this->response,[]),    
            Tools::getValueFromArray('postresult', $this->response,[])    
        );
        $form->show();
    }        
}
