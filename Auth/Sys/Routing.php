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

		$ref_class = new \ReflectionClass($class_name_action);

		$ref_params = $ref_class->getMethod('__invoke')->getParameters();

		$param_names = array_map(
			function ($ref_param) {
				return $ref_param->getName();
			},
			$ref_params
		);

		$ref_params_required = array_filter(
			$ref_params,
			function (\ReflectionParameter $ref_param) {
				return ! $ref_param->isOptional();
			}
		);

		$param_names_required = array_map(
			function ($ref_param) {
				return $ref_param->getName();
			},
			$ref_params_required
		);

		foreach ($param_names_required as $param_name_required)
		{
			if ( ! array_key_exists($param_name_required, $params)) {
				throw new \Exception(
					sprintf(
						'Обязательный GET параметр не передан "%s"',
						$param_name_required
					),
					404
				);
			}
		}

		// порядок аргументов важен!
		$params_extra = array_diff(
			array_keys($params),
			$param_names
		);
		if ( ! empty($params_extra))
		{
			throw new \Exception(
				sprintf(
					'Переданы лишние параметры "%s"',
					join(', ', $params_extra)
				),
				404
			);
		}

		$_params = [];
		foreach ($ref_params as $ref_param)
		{
			$_params[$ref_param->getName()] = array_key_exists($ref_param->getName(),$params)
                ? $params[$ref_param->getName()]
                : $ref_param->getDefaultValue();
		}

		call_user_func_array($action, $_params);
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