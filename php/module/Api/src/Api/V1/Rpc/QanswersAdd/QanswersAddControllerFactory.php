<?php
namespace Api\V1\Rpc\QanswersAdd;

class QanswersAddControllerFactory
{
    public function __invoke($controllers)
    {
        return new QanswersAddController();
    }
}
