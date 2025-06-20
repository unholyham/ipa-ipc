<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Account;
use App\Models\Company;
use App\Models\Project;
use App\Models\Proposal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProposalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        try {
            $userIrwan = Account::where('employeeName', 'Irwan')->firstOrFail();
            $userDave = Account::where('employeeName', 'Dave')->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('User not found during ProposalSeeder.', ['error' => $e->getMessage()]);
            return;
        }

        try {
            $medongproject = Project::where('projectTitle', 'Medong Bridge Project')->firstOrFail();
            $bmeproject = Project::where('projectTitle', 'BME Brandah Road Project')->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Project not found during ProposalSeeder.', ['error' => $e->getMessage()]);
            return;
        }

        Proposal::create([
            'ownerId' => $userDave->accountID,
            'project' => $medongproject->projectID,
            'region' => 'Sarawak',
            'preparedBy' => 'Employee 1',
            // 'mainContractor' => $shorefield->companyID,
            'reviewStatus' => 'Not Started',
            'checkedStatus' => 'Not Started',
            'approvedStatus' => 'Not Started',
            'remarks' => null,
            'pathToTP' => null,
            'pathToJMS' => null
        ]);

        Proposal::create([
            'ownerId' => $userIrwan->accountID,
            'project' => $bmeproject->projectID,
            'region' => 'Sarawak',
            'preparedBy' => 'Employee 2',
            // 'mainContractor' => $kurw->companyID,
            'reviewStatus' => 'Not Started',
            'checkedStatus' => 'Not Started',
            'approvedStatus' => 'Not Started',
            'remarks' => null,
            'pathToTP' => null,
            'pathToJMS' => null
        ]);

    }
}
