<?php

namespace Dnetix\Rbac\Repositories;

use Dnetix\Rbac\Contracts\RbacRepository;
use Dnetix\Rbac\Models\AuthenticatableRole;
use Dnetix\Rbac\Models\PermissionRole;
use Dnetix\Rbac\Models\Role;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Support\Collection;

/**
 * Implementation for the RBAC Repository based on Laravel's Eloquent Database
 */
class EloquentRbacRepository implements RbacRepository
{

    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Returns all the permissions configuration
     * @return array
     */
    public function getPermissions()
    {
        return $this->config->get('rbac.permissions');
    }

    /**
     * Return the permission configuration for the given slug
     * @param $slug
     * @return mixed
     */
    public function getPermissionConfiguration($slug)
    {
        return $this->config->get('rbac.permissions')[$slug];
    }

    /**
     * Returns the roles
     * @return Collection
     */
    public function getRoles()
    {
        return Role::all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getRoleById($id)
    {
        return Role::find($id);
    }

    /**
     * @param $slug
     * @return Role
     */
    public function getRoleBySlug($slug)
    {
        return Role::where('slug', $slug)->first();
    }

    /**
     * Returns the Roles assigned to a specific permission
     * @param $slug
     * @return Collection
     */
    public function getPermissionRoleByPermissionSlug($slug)
    {
        return PermissionRole::where('permission', $slug)->get();
    }

    /**
     * Returns the permissions allowed to a specific Role
     * @param $id
     * @return Collection
     */
    public function getPermissionRolesByRoleId($id)
    {
        return PermissionRole::where('role_id', $id)->get();
    }

    /**
     * Returns the roles assigned to an authenticatable (User)
     * @param $authenticatable
     * @return Collection
     */
    public function getRolesOfAuthenticatable(Authenticatable $authenticatable)
    {
        return Role::join('authenticatable_role', 'authenticatable_role.role_id', '=', 'roles.id')
            ->where('authenticatable_id', $authenticatable->getAuthIdentifier())
            ->where('authenticatable_type', get_class($authenticatable))
            ->get();
    }

    /**
     * Returns the authenticatables roles models that has a specific role you can fetch the authenticatable
     * property to obtain the authenticatable
     * @param $id
     * @return mixed
     */
    public function getAuthenticatableRolesByRoleId($id)
    {
        return AuthenticatableRole::where('role_id', $id)
            ->with(['authenticatable'])
            ->get();
    }

    /**
     * @param $authenticatable
     * @param $role
     * @return AuthenticatableRole
     */
    public function assignAuthenticatableToRole(Authenticatable $authenticatable, $role)
    {
        if($role instanceof Role){
            $role = $role->id();
        }
        
        return AuthenticatableRole::create([
            'authenticatable_id' => $authenticatable->getAuthIdentifier(),
            'authenticatable_type' => get_class($authenticatable),
            'role_id' => $role
        ]);
    }

    /**
     * Creates a new PermissionRole object that relates a permission slug to
     * a role
     * @param $permission
     * @param $role
     * @return PermissionRole
     */
    public function assignPermissionToRole($permission, $role)
    {
        if($role instanceof Role){
            $role = $role->id();
        }
        
        return PermissionRole::create([
            'permission' => $permission,
            'role_id' => $role
        ]);
    }

    /**
     * Returns all the roles that have the authenticatable and permission
     * @param Authenticatable $authenticatable
     * @param $permission
     * @return Collection
     */
    public function getRolesByAuthenticatableAndPermission(Authenticatable $authenticatable, $permission)
    {
        return Role::join('permission_role', 'roles.id', '=', 'permission_role.role_id')
            ->join('authenticatable_role', 'authenticatable_role.role_id', '=', 'roles.id')
            ->where('authenticatable_id', $authenticatable->getAuthIdentifier())
            ->where('authenticatable_type', get_class($authenticatable))
            ->where('permission_role.permission', $permission)
            ->get();
    }
}