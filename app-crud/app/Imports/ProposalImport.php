<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;



class ProposalImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
    }

    /**
     * @return int
     */
    public function headingRow(): int
    {
        return 1;
    }

    /**
     * Define validation rules for the imported data.
     * These rules are applied before the data is passed to the collection.
     * Ensure your Excel columns match these keys.
     * @return array
     */
    public function rules(): array
    {
        return [
            'project_title' => 'required|string|max:255', // Corresponds to Excel header 'Project Title'
            'project_number' => 'nullable|string|max:255', // Corresponds to Excel header 'Project Number'
            'region' => 'required|string|max:255', // Corresponds to Excel header 'Region'
            'prepared_by' => 'required|string|max:255', // Corresponds to Excel header 'Prepared By'
            'main_contractor' => 'required|string|max:255', // Corresponds to Excel header 'Main Contractor'
        ];
    }

    /**
     * Custom column names for mapping Excel headers to validation rules.
     * This is useful if your Excel headers have spaces or different casing.
     * The keys here should match the lowercase, snake_case versions of your Excel headers.
     * @return array
     */
    public function customValidationAttributes(): array
    {
        return [
            'project_title' => 'Project Title',
            'project_number' => 'Project Number',
            'region' => 'Region',
            'prepared_by' => 'Prepared By',
            'main_contractor' => 'Main Contractor',
        ];
    }
}
