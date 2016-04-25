<?php

namespace Dnetix\Rbac\Models;


use Illuminate\Database\Eloquent\Model;

class AuthenticatableRole extends Model
{

    protected $table = 'authenticatable_role';

    protected $fillable = [
        'authenticatable_id',
        'authenticatable_type',
        'role_id',
    ];

    /* Eloquent Relationships */

    public function authenticatable()
    {
    	return $this->morphTo();
    }

}