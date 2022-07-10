<?php
namespace Api\V1\Rpc\VideosAdd;

class VideosAddControllerFactory
{
    public function __invoke($controllers)
    {
        return new VideosAddController();
    }
}
