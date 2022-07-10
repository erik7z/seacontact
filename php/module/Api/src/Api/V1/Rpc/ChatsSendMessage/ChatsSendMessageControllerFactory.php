<?php
namespace Api\V1\Rpc\ChatsSendMessage;

class ChatsSendMessageControllerFactory
{
    public function __invoke($controllers)
    {
        return new ChatsSendMessageController();
    }
}
