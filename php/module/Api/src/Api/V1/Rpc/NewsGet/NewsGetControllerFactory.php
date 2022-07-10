<?php
namespace Api\V1\Rpc\NewsGet;

class NewsGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new NewsGetController();
    }
}
