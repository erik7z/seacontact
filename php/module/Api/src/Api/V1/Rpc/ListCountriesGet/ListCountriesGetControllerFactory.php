<?php
namespace Api\V1\Rpc\ListCountriesGet;

class ListCountriesGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new ListCountriesGetController();
    }
}
