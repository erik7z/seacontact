<?php
namespace Api\V1\Rpc\QanswersEdit;

class QanswersEditControllerFactory
{
    public function __invoke($controllers)
    {
        return new QanswersEditController();
    }
}
