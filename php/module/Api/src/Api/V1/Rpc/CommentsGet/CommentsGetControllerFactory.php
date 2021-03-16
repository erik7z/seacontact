<?php
namespace Api\V1\Rpc\CommentsGet;

class CommentsGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new CommentsGetController();
    }
}
