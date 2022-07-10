<?php
namespace Api\V1\Rpc\ProfileGet;

class ProfileGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new ProfileGetController();
    }
}
