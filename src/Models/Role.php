<?php

namespace Dnetix\Rbac\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $access_date_range
 */
class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'access_date_range'
    ];

    public function id()
    {
        return $this->id;
    }

    public function name()
    {
        return $this->name;
    }

    public function slug()
    {
        return $this->slug;
    }

    public function description()
    {
        return $this->description;
    }

    public function accessDateRange()
    {
        return $this->access_date_range ?: 'A';
    }

    /* Eloquent Relationships */

    public function permissionRoles()
    {
        return $this->belongsToMany(PermissionRole::class);
    }

    public function authenticatableRoles()
    {
        return $this->belongsToMany(AuthenticatableRole::class);
    }
}