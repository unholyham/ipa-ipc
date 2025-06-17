<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    protected $primaryKey = 'companyID';//Set $primaryKey manually if it isn't explicity 'id'
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'companyName',
        'registrationNumber',
        'companyEmail',
        'companyAddress',
        'contractorType',
    ];

    protected static function booted(): void
    {
        static::creating(function (Company $company) {
            if (empty($company->companyID)) {
                $company->companyID = (string) Str::uuid();
            }
        });
    }
}
