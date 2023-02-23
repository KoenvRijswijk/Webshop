<?php
require_once 'MainController.php';
class AjaxController extends MainController implements iController
{
	protected $responsetype;
    protected $func;
    protected $handler;
//=============================================================================
//=============================================================================
	public function handleRequest()
	{
		try
		{
		 	ob_start("ob_gzhandler");
		 	$this->func = Tools::_getVar('func', false, NOP);
		 	//Debug::_echo('API FUNC=['.$this->func.']'); --> add logging tool or something
		 	$this->createHandler();
            $this->executeHandler();
            ob_end_flush();
            //Debug::_echo("API FUNC OK"); --> logging or something to keep track of requests and errors.. 
		}
		// Executed only in PHP 7, will not match in PHP 5
		 catch (Throwable $t)
        {
            ob_end_clean();
            $this->showError($t);	
        }
        catch (Exception $e)
        {
            ob_end_clean();
            $this->showError($e);
        }	
		
	}	
//=============================================================================

	public function createHandler()
	{
		switch($this->func)
		{
			case "rating":
				require_once CLASSPATH.'JSON_rateproducts.class.php';
				$this->handler = new JSON_RateProducts();
				break;
			
			case "about":
				$ajaxmodel = new AjaxModel();
				$data = $ajaxmodel->showMainContent($func);
				header("Content-type: application/json");
				echo json_encode($data);
				break;

			case "updatecart":
				require_once CLASSPATH.'json_addtocart.class.php';
				$this->handler = new JSON_AddToCart();
				break;

			case "removefromcart":
				require_once CLASSPATH.'json_removefromcart.class.php';
				$this->handler = new JSON_RemoveFromCart();
				break;	
			
			case "checkcart":
				require_once CLASSPATH.'json_checkcartcount.php';
				$this->handler = new JSON_CheckCartCount();
				break;
			
			default:
				throw new Exception("<h1>OOPS : no action defined for [".$this->func."]</h1>");
		}
	}

//==============================================================================
    private function executeHandler()
    {
        if ($this->handler)
        {    
            $this->handler->execute();
        }    
    }
//==============================================================================
	private function showError($e)
    {
        $code = $e->getCode();
        header('HTTP/1.1 '.($code===0?'501':$code).' OOOPS');
        echo $e->getMessage();
        /*if (LOCALTEST)
        {    
            echo "<br/>file [".$e->getFile()."] line [".$e->getLine()."]";
            echo "<br/>".$e->getTraceAsString();
        }
        //Debug::_error($e); add logging or debugging tool.. 
        */
    }
//==============================================================================
    private function checkApiToken()
    {
        if (Utils::sesVar("gw_x6k4", "X") !== Utils::getVar("gw_x6k4", false, "Y"))
        {    
            throw new Exception("<h1>".$this->_crud->getWBTxt("001550", $this->langcode)."</h1>");
        }    
    }
//============================================================================= 

}