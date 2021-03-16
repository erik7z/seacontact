<?php
namespace Api\V1\Rpc\LikesAdd;

class LikesAddControllerFactory
{
    public function __invoke($controllers)
    {
        return new LikesAddController();
    }
}
