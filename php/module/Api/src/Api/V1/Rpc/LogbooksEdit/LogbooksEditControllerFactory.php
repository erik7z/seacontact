<?php
namespace Api\V1\Rpc\LogbooksEdit;

class LogbooksEditControllerFactory
{
    public function __invoke($controllers)
    {
        return new LogbooksEditController();
    }
}
