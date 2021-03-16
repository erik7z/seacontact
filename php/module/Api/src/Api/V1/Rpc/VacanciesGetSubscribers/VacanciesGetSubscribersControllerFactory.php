<?php
namespace Api\V1\Rpc\VacanciesGetSubscribers;

class VacanciesGetSubscribersControllerFactory
{
    public function __invoke($controllers)
    {
        return new VacanciesGetSubscribersController();
    }
}
