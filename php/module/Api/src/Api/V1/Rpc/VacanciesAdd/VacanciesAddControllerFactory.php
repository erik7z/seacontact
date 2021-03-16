<?php
namespace Api\V1\Rpc\VacanciesAdd;

class VacanciesAddControllerFactory
{
    public function __invoke($controllers)
    {
        return new VacanciesAddController();
    }
}
