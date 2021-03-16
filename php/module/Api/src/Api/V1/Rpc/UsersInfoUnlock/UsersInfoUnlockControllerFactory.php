<?php
namespace Api\V1\Rpc\UsersInfoUnlock;

class UsersInfoUnlockControllerFactory
{
    public function __invoke($controllers)
    {
        return new UsersInfoUnlockController();
    }
}
