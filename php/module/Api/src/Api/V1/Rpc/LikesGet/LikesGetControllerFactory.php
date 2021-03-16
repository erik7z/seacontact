<?php
namespace Api\V1\Rpc\LikesGet;

class LikesGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new LikesGetController();
    }
}
