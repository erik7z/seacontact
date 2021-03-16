<?php
namespace Api\V1\Rpc\QuestionsEdit;

class QuestionsEditControllerFactory
{
    public function __invoke($controllers)
    {
        return new QuestionsEditController();
    }
}
