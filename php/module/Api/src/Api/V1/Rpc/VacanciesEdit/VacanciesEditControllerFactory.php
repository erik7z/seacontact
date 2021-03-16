<?php
namespace Api\V1\Rpc\VacanciesEdit;

class VacanciesEditControllerFactory
{
    public function __invoke($controllers)
    {
        return new VacanciesEditController();
    }
}
