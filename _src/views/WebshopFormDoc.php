<?php
require_once "WebshopDoc.php";
require_once "HtmlForm.php";
class WebshopFormDoc extends WebshopDoc
{
//==============================================================================
    protected function showMainContent() 
    {
        parent::showMainContent(); 
        $form = new HtmlForm(
            Tools::getValueFromArray('forminfo',   $this->response,[]),    
            Tools::getValueFromArray('fieldinfo',  $this->response,[]),    
            Tools::getValueFromArray('postresult', $this->response,[]),    
        );
        $form->show();
    }        
}
