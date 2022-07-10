<?php
namespace Api\V1\Rpc\VacanciesToggleReport;

class VacanciesToggleReportControllerFactory
{
    public function __invoke($controllers)
    {
        return new VacanciesToggleReportController();
    }
}
