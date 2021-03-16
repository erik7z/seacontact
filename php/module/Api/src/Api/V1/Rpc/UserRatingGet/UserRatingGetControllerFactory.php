<?php
namespace Api\V1\Rpc\UserRatingGet;

class UserRatingGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new UserRatingGetController();
    }
}
