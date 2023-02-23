<?php
require_once 'MainController.php';
require_once SRC.'models/UserModel.php';
require_once SRC.'models/WebshopModel.php';
require_once SRC.'models/CartModel.php';
class PageController extends MainController implements iController
{
    protected array $request;
    protected array $response;
    protected ?BaseModel $basemodel = null;
    protected ?CartModel $cartmodel = null;
    protected ?UserModel $usermodel = null;
    protected ?WebShopModel $webshopmodel = null;
    protected ?HtmlDoc $doc = null;

//==============================================================================
// Implementation of iController interface    
//==============================================================================
    public function handleRequest()
    {
    try
        {
            ob_start(); 
            //throw new Exception('OOOOPS');
            $this->getRequest();
            $this->validateRequest();
            $this->showResponse();
            ob_end_flush();
        }
        catch(Exception $e) 
        {
            ob_end_clean();
            echo $e->getMessage(); 
        }
    }

//==============================================================================
    protected function getRequest()
    {
        $posted = ($_SERVER['REQUEST_METHOD']==='POST');
        $this->request = [
            'posted' => $posted,
            'page'   => Tools::getRequestVar('page', $posted, 'home')
        ];
    }
//==============================================================================
    protected function validateRequest()
    {
        $this->response = $this->request; // getoond == gevraagd
        if ($this->isPageAllowed())
        {    
            $this->request['posted']
            ? $this->handlePostRequest()
            : $this->handleGetRequest();
        }
        else
        {
            $this->doc = $this->getBaseModel()->createWebShopDoc($this->response);
        }
    }
//==============================================================================
    protected function showResponse()
    {
        if ($this->doc)
        {
            $this->doc->addCssFile('https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css');
            $this->doc->addCssFile('./assets/css/webshop.css');
           
            $this->doc->addJsFile('./assets/js/jquery-3.6.1.min.js', true);
            $this->doc->addJsFile('./assets/js/rate.js', false);
            $this->doc->addJsFile('./assets/js/updatecart.js', false);

            $this->doc->show();
        }  
// anders Errorpage?        
    }
//==============================================================================
    protected function isPageAllowed() : bool
    {
        if (UserModel::loggedUser())
        {    
            if (in_array($this->response['page'], ['login','register']))
            {
                $this->response[SYSERR] = 'Eerst uitloggen a.u.b.';
                $this->response['page'] = 'home';
                $this->response['posted'] = 'false';
                return false;
            }
        }    
        else
        {
            if (in_array($this->response['page'], ['addtocart','cart','logout','order','removefromcart']))
            {
                $this->response[SYSERR] = 'Eerst inloggen a.u.b.';
                $this->response['page'] = 'login';
                $this->response['posted'] = 'false';
                return false;
            }
        }
        return true;
    }    
//==============================================================================    
    protected function handlePostRequest()
    {
       //via usermodel get access to validate function in basemodel, it is returning the values in response['postresult']
       // tools::dump($this->response);
        if ($this->getUserModel()->validatePostedForm($this->response))
        {
            switch ($this->response['page'])
            {
                case 'contact': 
                    $this->doc = $this->usermodel->handleContact($this->response);
                    break;
                case 'login':
                    $this->doc = $this->usermodel->handleLogin($this->response);
                    break;
                case 'register':
                    $this->doc = $this->usermodel->handleRegistration($this->response);
                    break;
                case 'edit':
                //Tools::dump($this->response);
                    $this->doc = $this->getShopModel()->handleEditItem($this->response);
                    break;
                case 'nieuw_item':
                    $this->doc = $this->getadminmodel()->HandleNewItem($this->response);
                break;  
                case 'webshop':
                    $this->doc = $this->getShopModel()->handleWebshopItems($this->response);
                break;
            }
        }  
        else
        {
            $this->doc = $this->usermodel->createWebShopFormDoc($this->response);
        }
    }        
//==============================================================================
    protected function handleGetRequest()
    {
        switch ($this->response['page'])
        {
            case 'home':
            case 'about':
                $this->doc = $this->getBaseModel()->createWebShopDoc($this->response);
                break;
            case 'contact':
            case 'login':
            case 'register':
                $this->doc = $this->getBaseModel()->createWebShopFormDoc($this->response);
                break;
            case 'addtocart':
                $this->doc = $this->getCartModel()->handleAddToCart($this->response);
                break;
            case 'cart':
                $this->doc = $this->getCartModel()->handleViewCart($this->response);
                break;
            case 'detail':    
                $this->doc = $this->getShopModel()->handleItemDetail($this->response);
                break;
            case 'logout':
                $this->doc = $this->getUserModel()->handleLogout($this->response);
                break;
            case 'removefromcart':
                $this->doc = $this->getCartModel()->handleRemoveFromCart($this->response);
                break;
            case "order":    
                $this->doc = $this->getCartModel()->handleSaveOrder($this->response);
                break;
            case 'webshop':
                $this->doc = $this->getShopModel()->handleWebshopItems($this->response);
                break;
            case 'edit':
                $this->doc = $this->getShopModel()->createWebShopEditFormDoc($this->response);
                break;
            case 'editorder':
                $this->doc = $this->getShopModel()->handleEditOrder($this->response);
                break;
            case 'orders':
                $this->doc = $this->getShopModel()->handleWebshopOrders($this->response);
                break;    
        }        
    }    
//==============================================================================
//  CREATE WHEN NEEDED .... 
//==============================================================================
    protected function getBaseModel() : BaseModel
    {
        if (is_null($this->basemodel))
        {
            $this->basemodel = new BaseModel();
        }
        return $this->basemodel;    
    }    
//==============================================================================
    protected function getUserModel() : UserModel
    {
        if (is_null($this->usermodel))
        {
            $this->usermodel = new UserModel();
        }
        return $this->usermodel;    
    }    
//==============================================================================
    protected function getShopModel() : WebshopModel
    {
        if (is_null($this->webshopmodel))
        {
            $this->webshopmodel = new WebshopModel();
        }
        return $this->webshopmodel;    
    }    
//==============================================================================
    protected function getCartModel() : CartModel
    {
        if (is_null($this->cartmodel))
        {
            $this->cartmodel = new CartModel();
        }
        return $this->cartmodel;    
    }    
//============================================================================== 
}	

