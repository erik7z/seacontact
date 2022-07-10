<?php
namespace Api\V1\Rpc\UsersGet;

class UsersGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new UsersGetController();
    }
}
