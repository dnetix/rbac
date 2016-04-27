<?php

use Illuminate\Database\Capsule\Manager;

/**
 * Mocking the Facade for the migrations
 */
class Schema {
	public static function __callStatic($method, $args)
	{
		return call_user_func_array([Manager::schema(), $method], $args);
	}
}