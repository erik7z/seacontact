<?php
namespace Api\V1\Rpc\QuestionsChangeRating;

class QuestionsChangeRatingControllerFactory
{
    public function __invoke($controllers)
    {
        return new QuestionsChangeRatingController();
    }
}
