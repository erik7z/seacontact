<?php
namespace Api\V1\Rpc\ProfileAvatarUpload;

class ProfileAvatarUploadControllerFactory
{
    public function __invoke($controllers)
    {
        return new ProfileAvatarUploadController();
    }
}
