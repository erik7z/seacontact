<?php
namespace Api\V1\Rpc\QanswersChangeRating;

class QanswersChangeRatingControllerFactory
{
    public function __invoke($controllers)
    {
        return new QanswersChangeRatingController();
    }
}
