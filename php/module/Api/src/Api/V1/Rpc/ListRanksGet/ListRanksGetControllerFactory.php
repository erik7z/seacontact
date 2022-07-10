<?php
namespace Api\V1\Rpc\ListRanksGet;

class ListRanksGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new ListRanksGetController();
    }
}
