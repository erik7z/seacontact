<?php
namespace Api\V1\Rpc\ProfilePassReset;

class ProfilePassResetControllerFactory
{
    public function __invoke($controllers)
    {
        return new ProfilePassResetController();
    }
}
