<?php
namespace Api\V1\Rpc\LogbooksGet;

class LogbooksGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new LogbooksGetController();
    }
}
