<?php
namespace Api\V1\Rpc\ProfilePassForgot;

class ProfilePassForgotControllerFactory
{
    public function __invoke($controllers)
    {
        return new ProfilePassForgotController();
    }
}
