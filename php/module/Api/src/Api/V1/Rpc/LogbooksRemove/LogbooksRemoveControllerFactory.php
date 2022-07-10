<?php
namespace Api\V1\Rpc\LogbooksRemove;

class LogbooksRemoveControllerFactory
{
    public function __invoke($controllers)
    {
        return new LogbooksRemoveController();
    }
}
