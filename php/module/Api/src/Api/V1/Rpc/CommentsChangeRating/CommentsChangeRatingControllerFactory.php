<?php
namespace Api\V1\Rpc\CommentsChangeRating;

class CommentsChangeRatingControllerFactory
{
    public function __invoke($controllers)
    {
        return new CommentsChangeRatingController();
    }
}
