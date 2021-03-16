<?php
namespace Api\V1\Rpc\ProfileCvAvatarRemove;

class ProfileCvAvatarRemoveControllerFactory
{
    public function __invoke($controllers)
    {
        return new ProfileCvAvatarRemoveController();
    }
}
