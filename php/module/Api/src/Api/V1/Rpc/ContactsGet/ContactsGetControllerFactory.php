<?php
namespace Api\V1\Rpc\ContactsGet;

class ContactsGetControllerFactory
{
    public function __invoke($controllers)
    {
        return new ContactsGetController();
    }
}
