<?php

namespace App\Models;

use App\Models\Company;
use App\Models\Proposal;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
     protected $primaryKey = 'projectID';//Set $primaryKey manually if it isn't explicity 'id'
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'projectTitle',
        'projectNumber',
        'subContractor',
    ];

    protected static function booted(): void
    {
        static::creating(function (Project $project) {
            if (empty($project->projectID)) {
                $project->projectID = (string) Str::uuid();
            }
        });
    }

    public function subContractorCompany()
    {
        return $this->belongsTo(Company::class, 'subContractor', 'companyID');
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'project', 'projectID');
    }
}
