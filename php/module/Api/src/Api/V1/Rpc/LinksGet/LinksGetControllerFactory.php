<?php
namespace Api\V1\Rpc\LinksGet;

class LinksGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new LinksGetController();
    }
}
