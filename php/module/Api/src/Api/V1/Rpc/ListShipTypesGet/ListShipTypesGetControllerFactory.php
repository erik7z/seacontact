<?php
namespace Api\V1\Rpc\ListShipTypesGet;

class ListShipTypesGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new ListShipTypesGetController();
    }
}
