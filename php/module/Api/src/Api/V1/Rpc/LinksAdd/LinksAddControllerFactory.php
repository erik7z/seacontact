<?php
namespace Api\V1\Rpc\LinksAdd;

class LinksAddControllerFactory
{
    public function __invoke($controllers)
    {
        return new LinksAddController();
    }
}
