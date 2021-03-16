<?php
namespace Api\V1\Rpc\VideosGet;

class VideosGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new VideosGetController();
    }
}
