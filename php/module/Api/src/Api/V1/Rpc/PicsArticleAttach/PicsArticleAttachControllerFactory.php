<?php
namespace Api\V1\Rpc\PicsArticleAttach;

class PicsArticleAttachControllerFactory
{
    public function __invoke($controllers)
    {
        return new PicsArticleAttachController();
    }
}
