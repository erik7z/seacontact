<?php
namespace Api\V1\Rpc\ProfileCvAvatarUpload;

class ProfileCvAvatarUploadControllerFactory
{
    public function __invoke($controllers)
    {
        return new ProfileCvAvatarUploadController();
    }
}
