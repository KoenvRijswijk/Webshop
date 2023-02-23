<?php
require_once SRC.'ajax/json.class.php';
class JSON_RateProducts extends JSONHandler
{
//==============================================================================
    public function getData() : array
    {
    	$rate = array();
		$rate['MSG']   = "testing";
		$rate['value'] = Tools::_getVar("rateValue");
		$rate['item']  = Tools::_getVar("itemID");
		$rate['user']  = $_SESSION[USERID];
	 	
		//check if product exist
		if($this->getWebshopItemByID($rate['item']) !== false)
		{
		//check in database if this product is already rated by this user in table review_product	
			$row = $this->checkIfRatedBefore($rate['user'], $rate['item']);
			if($row !== false )
			{
			//if so: update current record for this user and product
				$row_id = $row['id'];
				//update query
				if($this->updateReview($rate['value'], $row_id) > 0)
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
				if($this->createReview($rate['item'], $rate['value'], $rate['user']))
				{
					$rate['MSG'] = "Bedankt je beoordeling is: !";
				}	
			}
		}
		else
		{
			$rate['MSG']   = "Product bestaat niet";
		}

		$result = $this->getProductAVGRating($rate['item']);
		$rate['avg'] = Tools::niceNumber($result['myAvg']);
		$count = $this->getCountRatingByItem($rate['item']);
		$rate['count'] = $count['count(*)'];
		$data   = array(
						 'message'=>''.$rate['MSG'].' '.$rate['value'],
						 'items'=>[]
		);
		$data['items'][] = array("target"=>"div#NrRating", 	"content" => "<div>klantbeoordeling(#".$rate['count']."): ".$rate['avg']);
		
		return $data;
    }
//==============================================================================
  	private function getWebshopItemByID(int $id) : array|false
    {
        return $this->_crud->selectOne(
            "SELECT products.id, productname, description, image, price, stock, active, count(product_rating.id) as nr_votes, round(avg(product_rating.rating),1) as avg_rate FROM `products` JOIN product_rating ON products.id = product_rating.product_id where products.id =:id",
            ['id' => [$id, true]]
        );
    }
//==============================================================================
	//check in database if this product is already rated by this user in table review_product	
	public function checkIfRatedBefore(int $id, int $item )
	{
		return $this->_crud->SelectOne(
			"SELECT * FROM product_rating WHERE user_id=:id and product_id=:item",
			[
				'id' => [$id, true],
				'item' => [$item, true]
			]
		);
	}
//==============================================================================
	public function updateReview(int $stars, int $id )
	{
		return $this->_crud->doUpdate(
			"UPDATE product_rating SET rating =:stars WHERE ID=:id",
			[
				'stars' => [$stars, false],
				'id'	=> [$id, false]
			]
		);
	}
//==============================================================================
	public function createReview(int $product_id, int $rating, int $user_id)
	{
		return $this->_crud->doInsert(
			"INSERT INTO product_rating (product_id, rating, user_id) VALUES (:id,:rating, :user)",
			[
				'id'	=> [$product_id, false],
				'rating' => [$rating, false],
				'user'	=> [$user_id, false]
			]
		);
	}
//==============================================================================
    public function getProductAVGRating(int $id)
    {
        return $this->_crud->selectOne(
            "SELECT round(AVG(rating),1) AS myAvg FROM product_rating WHERE product_id=:id",
            ['id' => [$id, true]]
        );
    }
//==============================================================================
    public function getCountRatingByItem(int $id)
    {
        return $this->_crud->selectOne(
            "SELECT count(*) FROM product_rating WHERE product_id=:id",
            ['id' => [$id, true]]
        );
    }
//==============================================================================
//==============================================================================
//==============================================================================
}