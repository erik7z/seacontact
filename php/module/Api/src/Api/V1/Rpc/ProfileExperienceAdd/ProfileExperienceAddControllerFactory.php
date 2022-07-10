<?php
namespace Api\V1\Rpc\ProfileExperienceAdd;

class ProfileExperienceAddControllerFactory
{
    public function __invoke($controllers)
    {
        return new ProfileExperienceAddController();
    }
}
