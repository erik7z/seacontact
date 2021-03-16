<?php
namespace Api\V1\Rpc\NotificationsGet;

class NotificationsGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new NotificationsGetController();
    }
}
