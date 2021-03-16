<?php
namespace Api\V1\Rpc\TagsGet;

class TagsGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new TagsGetController();
    }
}
