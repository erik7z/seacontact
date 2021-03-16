<?php
namespace Api\V1\Rpc\ProfileMenuGet;

class ProfileMenuGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new ProfileMenuGetController();
    }
}
