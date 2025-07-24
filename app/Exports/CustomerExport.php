<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Customer::all([
            'ac_number', 'full_name', 'mobile_number', 'email', 'status'
            // Add other fields as needed
        ]);
    }

    public function headings(): array
    {
        return [
            'ac_number', 'full_name', 'mobile_number', 'email', 'status'
            // Add other fields as needed
        ];
    }
}
