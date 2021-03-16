<?php
namespace Api\V1\Rpc\ProfileRegComplete;

class ProfileRegCompleteControllerFactory
{
    public function __invoke($controllers)
    {
        return new ProfileRegCompleteController();
    }
}
