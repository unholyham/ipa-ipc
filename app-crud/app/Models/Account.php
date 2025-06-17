<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Account extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'accountID';//Set $primaryKey manually if it isn't explicity 'id'
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'employeeName',
        'designation',
        'email',
        'password',
        'contactNumber',
        'verificationStatus',
        'verificationRejectRemarks',
        'accountStatus',
        'roleID',
        'companyID',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Account $account) {
            // Generate a UUID for AccountID if it's not already set
            if (empty($account->accountID)) {
                $account->accountID = (string) Str::uuid();
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'companyID', 'companyID');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'roleID', 'roleID');
    }
}
