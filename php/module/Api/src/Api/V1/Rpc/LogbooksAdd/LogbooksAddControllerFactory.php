<?php
namespace Api\V1\Rpc\LogbooksAdd;

class LogbooksAddControllerFactory
{
    public function __invoke($controllers)
    {
        return new LogbooksAddController();
    }
}
