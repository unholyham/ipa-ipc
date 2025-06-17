<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    protected $primaryKey = 'roleID';//Set $primaryKey manually if it isn't explicity 'id'
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'roleName',
        'roleDescription',
    ];

    protected static function booted(): void
    {
        static::creating(function (Role $role) {
            if (empty($role->roleID)) {
                $role->roleID = (string) Str::uuid();
            }
        });
    }
}
