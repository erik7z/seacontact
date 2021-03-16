<?php
namespace Api\V1\Rpc\ProfileExperienceRemove;

class ProfileExperienceRemoveControllerFactory
{
    public function __invoke($controllers)
    {
        return new ProfileExperienceRemoveController();
    }
}
