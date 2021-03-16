<?php
namespace Api\V1\Rpc\QanswersRemove;

class QanswersRemoveControllerFactory
{
    public function __invoke($controllers)
    {
        return new QanswersRemoveController();
    }
}
