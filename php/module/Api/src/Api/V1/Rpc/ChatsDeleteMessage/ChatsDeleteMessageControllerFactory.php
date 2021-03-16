<?php
namespace Api\V1\Rpc\ChatsDeleteMessage;

class ChatsDeleteMessageControllerFactory
{
    public function __invoke($controllers)
    {
        return new ChatsDeleteMessageController();
    }
}
