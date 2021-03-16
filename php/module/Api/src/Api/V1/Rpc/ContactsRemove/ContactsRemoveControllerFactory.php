<?php
namespace Api\V1\Rpc\ContactsRemove;

class ContactsRemoveControllerFactory
{
    public function __invoke($controllers)
    {
        return new ContactsRemoveController();
    }
}
