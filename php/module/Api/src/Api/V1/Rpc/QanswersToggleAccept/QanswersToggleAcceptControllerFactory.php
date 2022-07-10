<?php
namespace Api\V1\Rpc\QanswersToggleAccept;

class QanswersToggleAcceptControllerFactory
{
    public function __invoke($controllers)
    {
        return new QanswersToggleAcceptController();
    }
}
