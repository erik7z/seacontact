<?php
namespace Application\Exception;

abstract class StaticExcLog
{
	public static function log($e = null)
	{
		if(!$e) return false;
		$sl = \ServiceLocatorFactory\ServiceLocatorFactory::getInstance();
		$identity = $sl->get('AuthService')->getIdentity();
		$user_id = ($identity)? $identity->id : null;

		$routeMatch = $sl->get('ViewHelperManager')->get('routeMatch');
		$route_params = $routeMatch->getRouteParams();

		$sl->get('ExceptionsTable')->insertSingle([
				'user_id' => $user_id,
				'message' => $e->getMessage(),
				'code' => $e->getCode(),
				'file' => $e->getFile(),
				'line' => $e->getLine(),
				'trace' => $e->getTraceAsString(),
				'route' => $routeMatch->getMatchedRouteName(),
				'controller' => $route_params['controller'],
				'action'	=> $route_params['action'],
				'section' => $route_params['section'],
				'user' => $route_params['user'],
				'action_id' => $route_params['id'],
				'query' => (empty($routeMatch->getQuery()))? NULL : http_build_query($routeMatch->getQuery()),
				'hash' => md5($user_id.$e->getCode().$e->getFile().$e->getLine()),
				'count' => 1,
				'ip' => $_SERVER['REMOTE_ADDR'],
				'time' => time()
			], ['upd_dupl' => ['time', 'count' => ['increase' => 1]]]);
	}
}



