<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'companyName' => 'Shorefield Sdn Bhd',
            'registrationNumber' => '199301022698',
            'companyEmail' => 'mail@shorefield.com',
            'companyAddress' => 'Lot 1025, Block 7, MTLD, Lorong Demak Laut 7a, &, Jalan Demak Laut 7, Sejingkat Industrial Park, 93050 Kuching, Sarawak',
            'contractorType' => 'main contractor',
        ]);
        Company::create([
            'companyName' => 'Shorefield Communications Sdn Bhd',
            'registrationNumber' => '200801039006',
            'companyEmail' => 'general@shorecomm.my',
            'companyAddress' => 'Ground Floor Lot 5824 Sublot 13 Laman Cenderawasih, Phase 3, Jalan Semariang, Petra Jaya, 93050 Kuching, Sarawak',
            'contractorType' => 'main contractor',
        ]);
        Company::create([
            'companyName' => 'Konsortium URW Sdn Bhd',
            'registrationNumber' => '201501021229',
            'companyEmail' => 'mail@kurw.com',
            'companyAddress' => '32, Lorong Cenderawasih 6, Petra Jaya, 93050 Kuching, Sarawak',
            'contractorType' => 'main contractor',
        ]);
        Company::create([
            'companyName' => 'Medong Synergy',
            'registrationNumber' => '201901000788',
            'companyEmail' => 'medong@email.com',
            'companyAddress' => 'Lot 123, North Wing, Menara TM, Jalan Pantai Baharu, 50672 Kuala Lumpur, Malaysia',
            'contractorType' => 'sub contractor',
        ]);
        Company::create([
            'companyName' => 'BME Brandah',
            'registrationNumber' => '201801034292',
            'companyEmail' => 'bme@email.com',
            'companyAddress' => 'Building No 4, West Wing, Jalan Bukit Baharu, 87995 Kuala Lumpur, Malaysia',
            'contractorType' => 'sub contractor',
        ]);
    }
}
