<?php
require_once 'HtmlForm.php';
class HtmlDbForm  extends HtmlForm
{
    protected array $item;
    protected int $id;
//=============================================================================
    public function __construct(int $id, array $item, array $forminfo,array $fieldinfo, array $postresult=[])
    {
        parent::__construct($forminfo,$fieldinfo, $postresult);
        $this->id = $id;
        $this->item = $item;
    }
//=============================================================================
    protected function openForm()
    {
        parent::openForm();
        echo'	<input type="hidden" name="id" value="'.$this->id.'" />'.PHP_EOL;
    }
//==============================================================================
    protected function getFieldValue(string $name, array $info) : string   
    {
        if (isset($this->postresult[$name])) 
        {
            return $this->postresult[$name] ;
        }
        elseif (isset($this->item[$name]))
        {
            return $this->item[$name];
        }        
        else
        {
            return Tools::getValueFromArray('default', $info,'');
        }    
    }    
}
