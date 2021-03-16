<?php
namespace Api\V1\Rpc\VacanciesToggleSubscribe;

class VacanciesToggleSubscribeControllerFactory
{
    public function __invoke($controllers)
    {
        return new VacanciesToggleSubscribeController();
    }
}
