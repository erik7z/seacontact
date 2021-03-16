<?php
namespace Api\V1\Access;

use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ScAuthenticationAdapterDelegatorFactory implements DelegatorFactoryInterface
{
    public function createDelegatorWithName(
        ServiceLocatorInterface $services,
        $name,
        $requestedName,
        $callback
    ) {
        $listener = $callback();

        $config = $services->get('Config');
        if (! isset($config['zf-mvc-auth']['authentication']['adapters'])
            || ! is_array($config['zf-mvc-auth']['authentication']['adapters'])
        ) {
            return $listener;
        }

        foreach ($config['zf-mvc-auth']['authentication']['adapters'] as $type => $data) {
            if (! isset($data['adapter']) || ! is_string($data['adapter'])) {
                continue;
            }

            switch ($data['adapter']) {
                case 'ZF\MvcAuth\Authentication\HttpAdapter':
                    $adapter = AuthenticationHttpAdapterFactory::factory($type, $data, $services);
                    break;
                case 'Api\V1\Access\ScOAuth2Adapter':
                    $adapter = ScAuthenticationOAuth2AdapterFactory::factory($type, $data, $services);
                    break;
                case 'ZF\MvcAuth\Authentication\OAuth2Adapter':
                    $adapter = ScAuthenticationOAuth2AdapterFactory::factory($type, $data, $services);
                    break;
                default:
                    $adapter = false;
                    break;
            }

            if ($adapter) {
                $listener->attach($adapter);
            }
        }

        return $listener;
    }
}
