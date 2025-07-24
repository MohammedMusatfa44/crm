<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomerImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Customer([
            'ac_number' => $row['ac_number'],
            'full_name' => $row['full_name'],
            'mobile_number' => $row['mobile_number'],
            'email' => $row['email'],
            'status' => $row['status'],
            // Add other fields as needed
        ]);
    }

    public function rules(): array
    {
        return [
            '*.ac_number' => 'required|unique:customers,ac_number',
            '*.full_name' => 'required|string',
            '*.mobile_number' => 'required',
            '*.email' => 'nullable|email',
            '*.status' => ['required', Rule::in(['new','in_progress','follow_up','western','hot','closed'])],
        ];
    }
}
