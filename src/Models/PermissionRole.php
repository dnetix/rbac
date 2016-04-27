<?php

namespace Dnetix\Rbac\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $permission_id
 * @property string $role_id
 * 
 * @property Role $role
 */
class PermissionRole extends Model
{
    protected $table = 'permission_role';
    protected $fillable = [
        'permission',
        'role_id'
    ];

    public function id()
    {
        return $this->id;
    }

    public function permission()
    {
        return $this->permission;
    }

    public function roleId()
    {
        return $this->role_id;
    }

    /* Eloquent Relationships */
    
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}