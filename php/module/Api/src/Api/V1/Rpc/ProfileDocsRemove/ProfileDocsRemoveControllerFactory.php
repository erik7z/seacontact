<?php
namespace Api\V1\Rpc\ProfileDocsRemove;

class ProfileDocsRemoveControllerFactory
{
    public function __invoke($controllers)
    {
        return new ProfileDocsRemoveController();
    }
}
