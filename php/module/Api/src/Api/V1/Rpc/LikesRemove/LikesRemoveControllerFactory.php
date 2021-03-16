<?php
namespace Api\V1\Rpc\LikesRemove;

class LikesRemoveControllerFactory
{
    public function __invoke($controllers)
    {
        return new LikesRemoveController();
    }
}
