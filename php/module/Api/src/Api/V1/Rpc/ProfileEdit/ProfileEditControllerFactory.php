<?php
namespace Api\V1\Rpc\ProfileEdit;

class ProfileEditControllerFactory
{
    public function __invoke($controllers)
    {
        return new ProfileEditController();
    }
}
