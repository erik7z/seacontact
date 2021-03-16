<?php
namespace Api\V1\Access;

use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZF\MvcAuth\Factory\OAuth2ServerFactory;
final class ScAuthenticationOAuth2AdapterFactory
{
    /**
     * Intentionally empty and private to prevent instantiation
     */
    private function __construct()
    {
    }

    /**
     * Create and return an OAuth2Adapter instance.
     *
     * @param string|array $type
     * @param array $config
     * @param ServiceLocatorInterface $services
     * @return OAuth2Adapter
     * @throws ServiceNotCreatedException when missing details necessary to
     *     create instance and/or dependencies.
     */
    public static function factory($type, array $config, ServiceLocatorInterface $services)
    {
        if (! isset($config['storage']) || ! is_array($config['storage'])) {
            throw new ServiceNotCreatedException('Missing storage details for OAuth2 server');
        }

        return new ScOAuth2Adapter(
            OAuth2ServerFactory::factory($config['storage'], $services),
            $type
        );
    }
}
