<?php
namespace Api\V1\Rpc\ChatsGetList;

class ChatsGetListControllerFactory
{
    public function __invoke($controllers)
    {
        return new ChatsGetListController();
    }
}
