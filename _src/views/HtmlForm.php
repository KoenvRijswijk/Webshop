<?php
require_once SRC.'interfaces/iHtmlView.php';
class HtmlForm  implements iHtmlView
{
    protected array $forminfo;
    protected array $fieldinfo;
    protected array $postresult;
//=============================================================================
    public function __construct(array $forminfo,array $fieldinfo, array $postresult=[])
    {
        $this->forminfo = $forminfo;
        $this->fieldinfo = $fieldinfo;
        $this->postresult = $postresult;
    }
//==============================================================================
// Implementation of iHtmlView interface
//==============================================================================
    final public function show()
    {
        $this->openForm();
        $this->showFields();
        $this->closeForm();
    }
//=============================================================================
// Begin formulier met action, method en 
// hidden field met pagewaarde voor navigatie.
//=============================================================================
    protected function openForm()
    {
        //tools::dump($this->forminfo);
        $page = Tools::getValueFromArray('page', $this->forminfo, 'NOTSET');
        $action = Tools::getValueFromArray('action', $this->forminfo, '');
        $method = Tools::getValueFromArray('method', $this->forminfo, 'POST');
        echo '<form action="'.$action.'" method="'.$method.'" enctype="multipart/form-data" >'.PHP_EOL
            .'  <input type="hidden" name="page" value="'.$page.'" />'.PHP_EOL;
    }
//=============================================================================
// Toon submitbutton en form sluiten
//=============================================================================
    protected function closeForm()
    {
        $submit = Tools::getValueFromArray('submit', $this->forminfo, 'Submit');
        echo '  <button type="submit" value="submit">'.$submit.'</button>'.PHP_EOL
            .'</form>'.PHP_EOL;
    }
//=============================================================================
// Loop door alle velden en toon per veld-type het juiste inputfield.
// Zijn er geposte data meegegeven, toon dan of de value of de error.
//=============================================================================
    protected function showFields()
    {
        foreach ($this->fieldinfo as $name => $info)
        {
            
            //var_dump($info);
            $current_value = $this->getFieldValue($name, $info);
            echo '      <label for="'.$name.'">'
                .$info['label']
                .'</label><br />'
                .PHP_EOL;


            switch ($info['type'])
            {
                case "textarea" :
                    $this->showTextArea($name, $info,$current_value);
                    break;
                case "select" :
                    $this->showSelect($name, $info,$current_value);
                    break;
                default :   
                    $this->showInputField($name, $info, $current_value);
                    break;
            }
            echo '<br />'.PHP_EOL;
            if (isset($this->postresult[$name.'_err']))
            {
                    echo '  <span class="error">* '.$this->postresult[$name.'_err'].'</span><br/>';
            }           
        }
    }
//==============================================================================
    protected function getFieldValue(string $name, array $info) : string   
    {
        if (isset($this->postresult[$name])) 
        {
            return $this->postresult[$name] ;
        }
        else
        {
            return Tools::getValueFromArray('default', $info,'');
        }    
    }    
//==============================================================================
    protected function showTextArea(string $fieldname, array $fieldinfo, string $current_value)
    {
        echo '      <textarea name="'
            .$fieldname
            .'" placeholder="'
            .$fieldinfo['placeholder'].'">'
            .$current_value
            .'</textarea>'
            .PHP_EOL;    
    }
//==============================================================================
    protected function showSelect($fieldname, $fieldinfo, $current_value)
    {        
        echo '      <select name="'.$fieldname.'">'.PHP_EOL;
        $options = Tools::getValueFromArray('options',$fieldinfo,[]);
        if ($options)
        {
            foreach ($options as $key => $value)
            {
                $selected = $current_value==$value ? "selected" : "";
                echo '<option value="'.$value.'" '.$selected.'>'.$key.'</option>'.PHP_EOL;
            }    
        }
        echo '      </select>'.PHP_EOL;
    }
//==============================================================================
    protected function showInputField(string $fieldname, array $fieldinfo, string $current_value)
    {
        echo ' <input'
            .' type="'.$fieldinfo['type'].'"' 
            .' name="'.$fieldname.'"' 
            .' placeholder="'.$fieldinfo['placeholder'].'"'
            .' value="'.$current_value.'"'
            .' " />'
            .PHP_EOL;
    }        
//==============================================================================
}
