<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Account;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $adminRole = Role::where('roleName', 'admin')->firstOrFail();
            $userRole = Role::where('roleName', 'user')->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Role not found during AccountSeeder. Please ensure RoleSeeder runs first and creates "Administrator", "Employee", and "unassigned" roles.', ['error' => $e->getMessage()]);
            // Optionally, throw the exception to stop seeding if critical roles are missing
            // throw $e;
            return; // Stop execution if critical roles are missing
        }

        try {
            $shorefield = Company::where('companyName', 'Shorefield Sdn Bhd')->firstOrFail();
            $shorecomm = Company::where('companyName', 'Shorefield Communications Sdn Bhd')->firstOrFail();
            $kurw = Company::where('companyName', 'Konsortium URW Sdn Bhd')->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Company not found during AccountSeeder. Please ensure CompanySeeder runs first and creates "Default Company" and "ABC Contractors".', ['error' => $e->getMessage()]);
            // Optionally, throw the exception
            // throw $e;
            return; // Stop execution if critical companies are missing
        }

        Account::create([
            'employeeName' => 'Admin',
            'designation' => 'System Administrator',
            'email' => 'admin@email.com',
            'password' => '1234',
            'contactNumber' => '',
            'verificationStatus' => 'verified',
            'verificationRejectRemarks' => null,
            'accountStatus' => 'active',
            'roleID' => $adminRole->roleID,
            'companyID' => $shorefield->companyID,
            'email_verified_at' => now(),
        ]);

        Account::create([
            'employeeName' => 'Irwan',
            'designation' => 'Contract Executive',
            'email' => 'irwan@email.com',
            'password' => '1234',
            'contactNumber' => '123456789',
            'verificationStatus' => 'verified',
            'verificationRejectRemarks' => null,
            'accountStatus' => 'active',
            'roleID' => $userRole->roleID,
            'companyID' => $shorefield->companyID,
            'email_verified_at' => now(),
        ]);

        Account::create([
            'employeeName' => 'Dave',
            'designation' => 'Contract Executive',
            'email' => 'dave@email.com',
            'password' => '1234',
            'contactNumber' => '123456789',
            'verificationStatus' => 'verified',
            'verificationRejectRemarks' => null,
            'accountStatus' => 'active',
            'roleID' => $userRole->roleID,
            'companyID' => $shorecomm->companyID,
            'email_verified_at' => now(),
        ]);

        Account::create([
            'employeeName' => 'John',
            'designation' => 'Contract Executive',
            'email' => 'john@email.com',
            'password' => '1234',
            'contactNumber' => '123456789',
            'verificationStatus' => 'pending',
            'verificationRejectRemarks' => null,
            'accountStatus' => 'inactive',
            'roleID' => $userRole->roleID,
            'companyID' => $kurw->companyID,
            'email_verified_at' => now(),
        ]);

        Account::create([
            'employeeName' => 'Steve',
            'designation' => 'Contract Executive',
            'email' => 'steve@email.com',
            'password' => '1234',
            'contactNumber' => '123456789',
            'verificationStatus' => 'rejected',
            'verificationRejectRemarks' => 'Incorrect Details',
            'accountStatus' => 'inactive',
            'roleID' => $userRole->roleID,
            'companyID' => $shorecomm->companyID,
            'email_verified_at' => now(),
        ]);
    }
}
