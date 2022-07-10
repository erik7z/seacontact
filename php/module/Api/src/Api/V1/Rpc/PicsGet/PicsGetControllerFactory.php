<?php
namespace Api\V1\Rpc\PicsGet;

class PicsGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new PicsGetController();
    }
}
