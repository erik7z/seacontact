<?php
namespace Api\V1\Rpc\QuestionsGet;

class QuestionsGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new QuestionsGetController();
    }
}
