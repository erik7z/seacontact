<?php
namespace Api\V1\Rpc\ProfileDocsEdit;

class ProfileDocsEditControllerFactory
{
    public function __invoke($controllers)
    {
        return new ProfileDocsEditController();
    }
}
