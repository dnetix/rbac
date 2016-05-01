<?php

namespace Dnetix\Rbac\Contracts;

use Dnetix\Rbac\Models\AuthenticatableRole;
use Dnetix\Rbac\Models\PermissionRole;
use Dnetix\Rbac\Models\Role;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;

interface RbacRepository
{

    /**
     * Returns all the permissions configuration
     * @return array
     */
    public function getPermissions();

    /**
     * Return the permission configuration for the given slug
     * @param $slug
     * @return mixed
     */
    public function getPermissionConfiguration($slug);

    /**
     * Returns the roles
     * @return Collection
     */
    public function getRoles();

    /**
     * Persist in the database the role passed
     * @param Role $role
     * @return Role
     */
    public function storeRole(Role $role);
    
    /**
     * Persist in the database the role passed
     * @param Role $role
     * @return Role
     */
    public function updateRole(Role $role);

    /**
     * @param $id
     * @return mixed
     */
    public function getRoleById($id);

    /**
     * @param $slug
     * @return Role
     */
    public function getRoleBySlug($slug);

    /**
     * Returns the Roles assigned to a specific permission
     * @param $slug
     * @return Collection
     */
    public function getPermissionRoleByPermissionSlug($slug);

    /**
     * Returns an array with the slugs of the permissions associated with the role
     * @param $role
     * @return array
     */
    public function getPermissionsOfRole($role);

    /**
     * Returns the permissions allowed to a specific Role
     * @param $id
     * @return Collection
     */
    public function getPermissionRolesByRoleId($id);

    /**
     * Returns the roles assigned to an authenticatable (User)
     * @param $authenticatable
     * @return Collection
     */
    public function getRolesOfAuthenticatable(Authenticatable $authenticatable);

    /**
     * Returns the authenticatables roles models that has a specific role you can fetch the authenticatable
     * property to obtain the authenticatable
     * @param $id
     * @return mixed
     */
    public function getAuthenticatableRolesByRoleId($id);

    /**
     * @param $authenticatable
     * @param $role
     * @return AuthenticatableRole
     */
    public function assignAuthenticatableToRole(Authenticatable $authenticatable, $role);

    /**
     * Removes a role from an authenticatable
     * @param Authenticatable $authenticatable
     * @param $role
     * @return mixed
     */
    public function dissociateAuthenticatableOfRole(Authenticatable $authenticatable, $role);
    
    /**
     * Creates a new PermissionRole object that relates a permission slug to
     * a role
     * @param $permission
     * @param $role
     * @return PermissionRole
     */
    public function assignPermissionToRole($permission, $role);

    /**
     * @param $permission
     * @param $role
     * @return mixed
     */
    public function revokePermissionToRole($permission, $role);
    
    /**
     * Returns all the roles that have the authenticatable and permission
     * @param Authenticatable $authenticatable
     * @param $permission
     * @return Collection
     */
    public function getRolesByAuthenticatableAndPermission(Authenticatable $authenticatable, $permission);

}