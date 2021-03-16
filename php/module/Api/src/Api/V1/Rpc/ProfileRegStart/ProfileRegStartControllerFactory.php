<?php
namespace Api\V1\Rpc\ProfileRegStart;

class ProfileRegStartControllerFactory
{
    public function __invoke($controllers)
    {
        return new ProfileRegStartController();
    }
}
