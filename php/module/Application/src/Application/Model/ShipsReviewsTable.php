<?php
namespace Application\Model;


class ShipsReviewsTable extends zEmptyTable
{

	public function __construct()
	{
		$this->init('ships-reviews');
	}

	public function getReviews($limit = 10, $offset = 0)
	{
		return [];
	}

	public function getReview($review_id)
	{
		return [];
	}

	public function getReviewPics($review_id)
	{
		return [];
	}


}
