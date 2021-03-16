<?php
namespace Api\V1\Rpc\CommentsAdd;

class CommentsAddControllerFactory
{
    public function __invoke($controllers)
    {
        return new CommentsAddController();
    }
}
