<?php
namespace Api\V1\Rpc\QuestionsToggleSubscribe;

class QuestionsToggleSubscribeControllerFactory
{
    public function __invoke($controllers)
    {
        return new QuestionsToggleSubscribeController();
    }
}
