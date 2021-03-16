<?php
namespace Api\V1\Rpc\PicsArticleRemove;

class PicsArticleRemoveControllerFactory
{
    public function __invoke($controllers)
    {
        return new PicsArticleRemoveController();
    }
}
