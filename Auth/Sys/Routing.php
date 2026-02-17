<?php
namespace Auth\Sys;

use Auth\App\Action\_Base;

class Routing
{
	public static function getUrl($class_name_action, array $params = []): string
	{
		self::isValidAction($class_name_action);

		$params = empty($params) ? [] : $params;

		if (array_key_exists(ACTION_NAME, $params)) {
			throw new \Exception('Params cannot contain key "'.ACTION_NAME.'"');
		}

		return __URL_AUTH_ROOT__.'?'.
			http_build_query(
				array_filter(
					array_merge(
						[ACTION_NAME => $class_name_action],
						$params
					)
				)
			);
	}


	public static function run()
	{
		$class_name_action = $_GET[ACTION_NAME];

		$params = $_GET;

		unset($params[ACTION_NAME]);

		self::runAction($class_name_action, $params);
	}

	public static function runAction($class_name_action, array $params = [])
	{
		self::isValidAction($class_name_action);

		$action = new $class_name_action;

		call_user_func_array($action, $params);
	}


	private static function isValidAction($class_name_action)
	{
		if (empty($class_name_action)) {
			throw new \Exception('Action required');
		}

		if ( ! class_exists($class_name_action, true))
		{
			throw new \Exception(
				sprintf(
					'Action not found "%s"',
					$class_name_action
				)
			);
		};

		if ( ! is_subclass_of($class_name_action, \Auth\App\Action\_Base::class))
		{
			throw new \Exception(
				sprintf(
					'Class is not action "%s"',
					$class_name_action
				)
			);
		}
	}
}