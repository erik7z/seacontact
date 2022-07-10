<?php
namespace Api\V1\Access;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Adapter\Driver\Pdo\Pdo as PdoDriver;

class ScPdoAdapterFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ScPdoAdapter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $connection = $serviceLocator->get('db');
        if (!$connection->getDriver() instanceof PdoDriver) {
            throw new \RuntimeException("Need a PDO connection!");
        }

        $pdo = $connection->getDriver()->getConnection()->getResource();
        return new ScPdoAdapter($pdo);
    }  
}
