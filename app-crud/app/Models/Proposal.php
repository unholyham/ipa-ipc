<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Proposal extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'ownerID',
        'project',
        'region',
        'preparedBy',
        'reviewStatus',
        'checkedStatus',
        'approvedStatus',
        'remarks',
        'pathToTP',
        'pathToJMS'
    ];

    protected static function booted(): void
    {
        static::creating(function (Proposal $proposal) {
            // Generate a UUID for Proposal if it's not already set
            if (empty($proposal->id)) {
                $proposal->id = (string) Str::uuid();
            }
        });
    }

    public function owner() {
        return $this->belongsTo(Account::class, 'ownerID', 'accountID');
    }

    public function getProject() {
        return $this->belongsTo(Project::class, 'project', 'projectID');
    }
}
