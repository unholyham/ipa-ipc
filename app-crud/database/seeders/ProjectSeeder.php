<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $medong = Company::where('companyName', 'Medong Synergy')->firstOrFail();
            $bme = Company::where('companyName', 'BME Brandah')->firstOrFail();
            $shorefield = Company::where('companyName', 'Shorefield Sdn Bhd')->firstOrFail();
            $shorecomm = Company::where('companyName', 'Shorefield Communications Sdn Bhd')->firstOrFail();
            $kurw = Company::where('companyName', 'Konsortium URW Sdn Bhd')->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Company not found during ProjectSeeder.', ['error' => $e->getMessage()]);
            return;
        }

        Project::create([
            'projectTitle' => 'Medong Bridge Project',
            'projectNumber' => 'MD2025041',
            'subContractor' => $medong->companyID,
            'mainContractor' => $shorefield->companyID
        ]);
        Project::create([
            'projectTitle' => 'BME Brandah Road Project',
            'projectNumber' => 'BME2025041',
            'subContractor' => $bme->companyID,
            'mainContractor' => $shorecomm->companyID
        ]);

    }
}
