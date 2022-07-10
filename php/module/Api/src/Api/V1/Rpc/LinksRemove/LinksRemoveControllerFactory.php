<?php
namespace Api\V1\Rpc\LinksRemove;

class LinksRemoveControllerFactory
{
    public function __invoke($controllers)
    {
        return new LinksRemoveController();
    }
}
