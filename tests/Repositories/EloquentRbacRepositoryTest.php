<?php

class EloquentRbacRepositoryTest extends BaseTestCase
{
	/**
	 * @var \Dnetix\Rbac\Repositories\EloquentRbacRepository
	 */
	protected $repository;
	
	public function setUp()
	{
		parent::setUp();
		$this->repository = $this->getRepository();
	}

	/**
	 * @param array $attributes
	 * @return \Dnetix\Rbac\Models\Role
	 */
	public function createRole($attributes = []){
		$faker = \Faker\Factory::create();
		return \Dnetix\Rbac\Models\Role::create(array_merge([
			'name' => $faker->name,
			'slug' => $faker->slug,
			'description' => $faker->text
		], $attributes));
	}

	/**
	 * @param array $attributes
	 * @return \Dnetix\Rbac\Models\AuthenticatableTest
	 */
	public function createUser($attributes = [])
	{
		$faker = \Faker\Factory::create();
		return \Dnetix\Rbac\Models\AuthenticatableTest::create(array_merge([
			'name' => $faker->name,
			'email' => $faker->email,
			'password' => $faker->password
		], $attributes));
	}

	public function getRepository()
	{
		$config = \Mockery::mock(\Illuminate\Contracts\Config\Repository::class)
			->shouldReceive('get')
			->andReturn([
				'permission.test' => [
					'name' => 'Permiso de Prueba'
				]
			])
			->mock();
		return new \Dnetix\Rbac\Repositories\EloquentRbacRepository($config);
	}

	private function assignPermissionToRole($permission, $role)
	{
		$this->repository->assignPermissionToRole($permission, $role);
	}

	public function testItGetsAllThePermissionsFromTheConfigFile()
	{
		$this->assertNotEmpty($this->repository->getPermissions());
	}

	public function testItGetsOneSpecificPermissionByItsSlug()
	{
		$extra = $this->repository->getPermissionConfiguration('permission.test');
		$this->assertArrayHasKey('name', $extra);
	}

	public function testItGetsAllTheRolesAndById()
	{
		$this->setUpDatabase();
		
		// Create two roles
		$this->createRole();
		$testRole = $this->createRole();
		
		$roles = $this->repository->getRoles();
		
		$this->assertEquals(2, $roles->count());
		
		$role = $this->repository->getRoleById(1);
		$this->assertEquals(1, $role->id());
		$this->assertNotNull($role);
		
		$role = $this->repository->getRoleBySlug($testRole->slug());
		$this->assertEquals($role->id(), $testRole->id());
		$this->assertEquals($role->name(), $testRole->name());
		$this->assertEquals($role->description(), $testRole->description());
	}

	public function testItFetchesAllThePermissionsOfOneRole()
	{
		$this->setUpDatabase();
		$role = $this->createRole();

		$this->assignPermissionToRole('permission.test', $role);
		$this->assignPermissionToRole('permission.test1', $role);
		$this->assignPermissionToRole('permission.test2', $role);
		
		// Add noise to check correck lookup
		$role = $this->createRole();
		$this->assignPermissionToRole('permission.test', $role);
		$this->assignPermissionToRole('permission.test1', $role);
		$this->assignPermissionToRole('permission.test2', $role);
		
		$permissions = $this->repository->getPermissionRolesByRoleId($role->id());
		
		$this->assertEquals(3, $permissions->count());
		
		$permissions = $this->repository->getPermissionRoleByPermissionSlug('permission.test');
		
		$this->assertEquals(2, $permissions->count());
	}

	public function testItGetsAllTheRolesForAnAuthenticatable()
	{
		$this->setUpDatabase();
		
		$user = $this->createUser();
		
		$role_1 = $this->createRole();
		$role_2 = $this->createRole();
		
		$this->repository->assignAuthenticatableToRole($user, $role_1);
		$this->repository->assignAuthenticatableToRole($user, $role_2);
		
		$roles = $this->repository->getRolesOfAuthenticatable($user);
		
		$this->assertEquals(2, $roles->count());
		
		$this->assertEquals(2, $roles->intersect([$role_1, $role_2])->count());
	}
	
	public function testItGetsAllTheRolesForAnAuthenticatableAndAPermission()
	{
		$this->setUpDatabase();

		$user_1 = $this->createUser();
		$user_2 = $this->createUser();

		$role_1 = $this->createRole();
		$role_2 = $this->createRole();

		$this->repository->assignAuthenticatableToRole($user_1, $role_1);
		$this->repository->assignAuthenticatableToRole($user_1, $role_2);
		$this->repository->assignAuthenticatableToRole($user_2, $role_2);

		$this->assignPermissionToRole('permission.test', $role_1);
		$this->assignPermissionToRole('permission.test1', $role_1);
		$this->assignPermissionToRole('permission.test', $role_2);
		
		$roles = $this->repository->getRolesByAuthenticatableAndPermission($user_1, 'permission.test');
		$this->assertEquals(2, $roles->count());
		
		$roles = $this->repository->getRolesByAuthenticatableAndPermission($user_1, 'permission.test1');
		$this->assertEquals(1, $roles->count());
	}

	public function testItGetsAuthenticatablesOfRole()
	{
		$this->setUpDatabase();
		
		$user_1 = $this->createUser();
		$user_2 = $this->createUser();

		$role_1 = $this->createRole();
		$role_2 = $this->createRole();

		$this->repository->assignAuthenticatableToRole($user_1, $role_1);
		$this->repository->assignAuthenticatableToRole($user_1, $role_2);
		$this->repository->assignAuthenticatableToRole($user_2, $role_2);
		
		$authenticatables = $this->repository->getAuthenticatableRolesByRoleId($role_2->id());
		$this->assertSame(2, $authenticatables->count());
		
		$authenticatables = $this->repository->getAuthenticatableRolesByRoleId($role_1->id());
		$this->assertSame(1, $authenticatables->count());
		
		$user = $authenticatables->first()->authenticatable;
		$this->assertEquals($user_1->id(), $user->id());
	}

}