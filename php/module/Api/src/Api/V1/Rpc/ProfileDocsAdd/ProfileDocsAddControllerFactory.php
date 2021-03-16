<?php
namespace Api\V1\Rpc\ProfileDocsAdd;

class ProfileDocsAddControllerFactory
{
    public function __invoke($controllers)
    {
        return new ProfileDocsAddController();
    }
}
