<?php

namespace Dnetix\Rbac\Contracts;


use Dnetix\Rbac\Models\AuthenticatableRole;
use Dnetix\Rbac\Models\Role;
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
     * Returns the permissions allowed to a specific Role
     * @param $id
     * @return Collection
     */
    public function getPermissionRoleByRoleId($id);

    /**
     * Returns the roles assigned to an authenticatable (User)
     * @param $authenticatable
     * @return mixed
     */
    public function getRolesOfAuthenticatable($authenticatable);

    /**
     * Returns the authenticatables that has a specific role
     * @param $id
     * @return mixed
     */
    public function getAuthenticatablesOfRoleById($id);

    /**
     * @param $authenticatable
     * @param $role
     * @return AuthenticatableRole
     */
    public function assignRoleToAuthenticatable($authenticatable, $role);

}