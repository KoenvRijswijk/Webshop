<?php
require_once SRC.'dal/SiteDAO.php';
require_once SRC.'views/HtmlDoc.php';

class BaseModel
{
    protected SiteDAO $sitedao;

//==============================================================================
    public function __construct() 
    {
        $this->sitedao = new SiteDAO();
    }
//==============================================================================
    public static function loggedUser() : bool
    {
        return Tools::getSesVar(USERID, 'NOP') !== 'NOP';
    }    
//==============================================================================
    public static function hasCart() : bool
    {
        return count(Tools::getValueFromArray(CART, $_SESSION, [])) > 0;
    }   
//==============================================================================
    public function validatePostedForm(&$response) : bool
    {
        require_once SRC."tools/FormValidator.php";
        $validator = new FormValidator();
        $response['fieldinfo'] = $this->sitedao->getFieldInfoByPage($response['page']); 
        $response['postresult'] = $validator->checkFields($response['fieldinfo']);
        return $response['postresult']['ok'];
    }    
    
//==============================================================================
    public function createWebshopDoc(&$response) : HtmlDoc
    {
        $this->updateResponse($response);
        require_once SRC.'views/WebShopDoc.php';
        return new WebShopDoc($response);
    }    
//==============================================================================
    public function createWebShopFormDoc(&$response) : HtmlDoc
    {
        require_once SRC.'views/WebShopFormDoc.php';
        $this->updateResponse($response);
        $response['forminfo'] = $this->sitedao->getFormInfoByPage($response['page']);
        $response['fieldinfo'] = $this->sitedao->getFieldInfoByPage($response['page']);
        return new WebShopFormDoc($response);
    }    
//==============================================================================
  
//==============================================================================
    protected function updateResponse(&$response)
    {
        $loggeduser = $this->loggedUser();
        $hascart    = $this->hasCart();
        $response['loggeduser'] = $loggeduser;
        $response['menuitems'] = $this->sitedao->getMenuItems(
                $loggeduser,
                $hascart
        );        
        $response['bodytext'] = $this->sitedao->getTextByPage($response['page']);
    }        
//==============================================================================
    protected function addErrorMsg(&$response, string $e)
    {
        if ($response[SYSERR])
        {    
            $response[SYSERR] .= '<br/>'.$e;
        }    
        else
        {
            $response[SYSERR] = $e;
        }
        
    }
}
