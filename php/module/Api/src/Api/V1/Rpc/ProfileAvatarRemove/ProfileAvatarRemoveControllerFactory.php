<?php
namespace Api\V1\Rpc\ProfileAvatarRemove;

class ProfileAvatarRemoveControllerFactory
{
    public function __invoke($controllers)
    {
        return new ProfileAvatarRemoveController();
    }
}
