<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;


class Proposal extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'id',
        'ownerId',
        'projectTitle',
        'projectNumber',
        'region',
        'preparedBy',
        'mainContractor',
        'reviewStatus',
        'approvedStatus',
        'pathToTP',
        'pathToJMS'
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    public function owner() {
        return $this->belongsTo(User::class, 'ownerId');
    }
}
