<?php
require_once 'BaseModel.php';
require_once SRC.'/dal/ShopDAO.php';
class ApiModel extends BaseModel
{
    protected $responsetype;
    protected $func; 
    protected $handler; 
//==============================================================================
    public function __construct(string $request, $id = '', string $type)
    {
        $this->type = $type;
        $this->request = $request;
        $this->shopdao = new ShopDAO();
    }

//==============================================================================   
	public function handleRequest($type, $data)
	{
		switch($type)
		{
			case "json":
				header("Content-type: application/json");
				echo json_encode($data);
				break;

			case "xml":
				require_once .SRC'/tools/XMLGenerator.php';
				$xmlGen = new XmlGenerator;

				header("Content-Type: text/xml");
				print $xml->saveXML();
				break;

			case "html":
			default:
				header("Content-Type: text/html");
				echo 'html data conversion not yet created';
				break;
		}
		
	}
//==============================================================================
	public function _getWebshopItems($request)
	{
		$data = $this->shopdao->getWebshopItems(0, 25);
		$this->_handleHeader($this->type, $data);
	}
//==============================================================================

	public function _getWebshopItemById($request, $id)
	{
		$data = $this->shopdao->getWebshopItemByID($id);
		$this->_handleHeader($this->type, $data);
	}
//==============================================================================
}
