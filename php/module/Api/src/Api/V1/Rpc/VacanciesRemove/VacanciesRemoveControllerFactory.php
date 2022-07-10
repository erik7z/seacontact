<?php
namespace Api\V1\Rpc\VacanciesRemove;

class VacanciesRemoveControllerFactory
{
    public function __invoke($controllers)
    {
        return new VacanciesRemoveController();
    }
}
