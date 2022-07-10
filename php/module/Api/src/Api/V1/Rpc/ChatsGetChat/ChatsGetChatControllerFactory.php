<?php
namespace Api\V1\Rpc\ChatsGetChat;

class ChatsGetChatControllerFactory
{
    public function __invoke($controllers)
    {
        return new ChatsGetChatController();
    }
}
