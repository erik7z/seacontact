<?php
namespace Api\V1\Rpc\CommentsEdit;

class CommentsEditControllerFactory
{
    public function __invoke($controllers)
    {
        return new CommentsEditController();
    }
}
