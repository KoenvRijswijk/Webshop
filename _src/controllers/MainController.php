<?php 
require_once SRC.'interfaces/iController.php';

Class MainController implements iController
{
	protected $_action;
//=============================================================================
	public function __construct()
	{
		$this->_action = $this->_getVar("action", "page");
	}

//============================================================================= 	
	public function handleRequest()
	{
		switch($this->_action)
		{
			case 'api':
				require_once SRC.'controllers/ApiController.php';
				$controller = new ApiController();
				break;

			case 'ajaxcall':
				require_once SRC.'controllers/AjaxController.php';
				$controller = new AjaxController();
				break;
		
			case 'page':
			default:
				require_once SRC.'controllers/PageController.php';
				$controller = new PageController();
				break;
		}
		$controller->handlerequest();

	}
//============================================================================= 
	protected function _getVar($name, $default="NOPPES")
	{
		return isset($_GET[$name]) ? $_GET[$name] : $default;
	}

//============================================================================= 

}