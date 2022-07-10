<?php
namespace Api\V1\Rpc\QuestionsRemove;

class QuestionsRemoveControllerFactory
{
    public function __invoke($controllers)
    {
        return new QuestionsRemoveController();
    }
}
