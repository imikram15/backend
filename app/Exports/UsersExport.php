<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;

class UsersExport implements FromCollection, WithStyles,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::all();
    }
    /**
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     */
    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet) {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],

        ];
    }
   
    
    /**
     * @return array
     */
    public function headings(): array {
        return [
            '#',
            'Emails',
            'Login Access',
            'Member Type',
            'Role ID',
            'Created At',
        ];
    }
}
