<?php
namespace Api\V1\Rpc\VacanciesGet;

class VacanciesGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new VacanciesGetController();
    }
}
