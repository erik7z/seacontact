<?php
namespace Api\V1\Rpc\UserExperienceGet;

class UserExperienceGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new UserExperienceGetController();
    }
}
