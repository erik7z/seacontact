<?php
namespace Api\V1\Rpc\LikesIsLiked;

class LikesIsLikedControllerFactory
{
    public function __invoke($controllers)
    {
        return new LikesIsLikedController();
    }
}
