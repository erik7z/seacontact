<?php
namespace Api\V1\Rpc\QanswersGet;

class QanswersGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new QanswersGetController();
    }
}
