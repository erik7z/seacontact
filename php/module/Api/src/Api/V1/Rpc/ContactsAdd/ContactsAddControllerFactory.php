<?php
namespace Api\V1\Rpc\ContactsAdd;

class ContactsAddControllerFactory
{
    public function __invoke($controllers)
    {
        return new ContactsAddController();
    }
}
