<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class BaseTestCase extends PHPUnit_Framework_TestCase
{
	public $db;

	public function setUpDatabase()
	{
		$capsule = new Capsule;

		$capsule->addConnection([
			'driver' => 'sqlite',
			'database' => ':memory:'
		]);

		$capsule->setAsGlobal();
		$capsule->bootEloquent();

		$this->db = true;

		$this->runMigrations();
	}

	public function runMigrations()
	{
		$migration = new CreateRbacModule;
		$migration->up();
		$migration = new CreateUsersTestTable;
		$migration->up();
	}

	public function tearDown()
	{
		if($this->db){
			$migration = new CreateRbacModule;
			$migration->down();
			$migration = new CreateUsersTestTable;
			$migration->down();
		}
	}
}