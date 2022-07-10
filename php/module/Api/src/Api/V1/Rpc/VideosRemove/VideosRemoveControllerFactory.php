<?php
namespace Api\V1\Rpc\VideosRemove;

class VideosRemoveControllerFactory
{
    public function __invoke($controllers)
    {
        return new VideosRemoveController();
    }
}
