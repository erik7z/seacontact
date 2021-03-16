<?php
namespace Api\V1\Rpc\QuestionsAdd;

class QuestionsAddControllerFactory
{
    public function __invoke($controllers)
    {
        return new QuestionsAddController();
    }
}
