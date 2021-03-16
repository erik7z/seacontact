<?php
namespace Api\V1\Rpc\ProfileExperienceEdit;

class ProfileExperienceEditControllerFactory
{
    public function __invoke($controllers)
    {
        return new ProfileExperienceEditController();
    }
}
