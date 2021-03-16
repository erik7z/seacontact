<?php
namespace Api\V1\Rpc\SiteInfoGet;

class SiteInfoGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new SiteInfoGetController();
    }
}
