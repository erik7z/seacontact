<?php
namespace Api\V1\Rpc\UserDocsGet;

class UserDocsGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new UserDocsGetController();
    }
}
