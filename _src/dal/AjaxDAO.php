<?php 
require_once "BaseDAO.php";
class AjaxDAO extends BaseDAO 
{
//==============================================================================
    public function getWebshopItemByID(int $id) : array|false
    {
        return $this->_crud->selectOne(
            "SELECT * FROM products WHERE id=:id",
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
            "SELECT AVG(rating) AS myAvg FROM product_rating WHERE product_id=:id",
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
}