<?php
require_once 'BaseModel.php';
require_once SRC.'/dal/AjaxDAO.php';

class AjaxModel extends BaseModel
{
    protected AjaxDAO $ajaxdao;

//==============================================================================
    public function __construct()
    {
        //parent::__construct();
        $this->ajaxdao = new AjaxDAO();
    }
//==============================================================================

    public function setAjaxReview() : array
    {
    	$rate = array();
		$rate['MSG']   = "testing";
		$rate['value'] = Tools::_getVar("rateValue");
		$rate['item']  = Tools::_getVar("itemID");
		$rate['user']  = $_SESSION[USERID];
	 	
		//check if product exist
		if($this->ajaxdao->getWebshopItemByID($rate['item']) !== false)
		{
		//check in database if this product is already rated by this user in table review_product	
			$row = $this->ajaxdao->checkIfRatedBefore($rate['user'], $rate['item']);
			if($row !== false )
			{
			//if so: update current record for this user and product
				$row_id = $row['id'];
				//update query
				if($this->ajaxdao->updateReview($rate['value'], $row_id) > 0)
				{
					$rate['MSG'] = "Bedankt, je aangepaste boordeling is: ";
				}
				else
				{
					$rate['MSG'] = "Bedankt, dit cijfer heeft u al eerder opgegeven: ";
				}
			}
			else
			{
				if($this->ajaxdao->createReview($rate['item'], $rate['value'], $rate['user']))
				{
					$rate['MSG'] = "Bedankt je beoordeling is: !";
				}	
			}
		}
		else
		{
			$rate['MSG']   = "Product bestaat niet";
		}
		$result = $this->ajaxdao->getProductAVGRating($rate['item']);
		$rate['avg'] = Tools::niceNumber($result['myAvg']);
		$count = $this->ajaxdao->getCountRatingByItem($rate['item']);
		$rate['count'] = $count['count(*)'];
		$data   = array(
						 'message'=>''.$rate['MSG'].' '.$rate['value'],
						 'items'=>[]
		);
		$data['items'][] = array("target"=>"div#NrRating", 	"content" => "<div>klantbeoordeling(#".$rate['count']."): ".$rate['avg']);
		
		return $data;
    }
//==============================================================================

//==============================================================================
}