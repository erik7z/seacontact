<?php
namespace Api\V1\Rpc\CommentsRemove;

class CommentsRemoveControllerFactory
{
    public function __invoke($controllers)
    {
        return new CommentsRemoveController();
    }
}
