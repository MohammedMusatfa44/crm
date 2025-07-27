<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromCollection, WithHeadings
{
    protected $customerIds;

    public function __construct($customerIds = null)
    {
        $this->customerIds = $customerIds;
    }

    public function collection()
    {
        $fields = [
            'id',
            'ac_number',
            'full_name',
            'mobile_number',
            'email',
            'comment',
            'status',
            'assigned_employee_id',
            'sub_department_id',
            'complaint_reason',
            'nationality',
            'city',
            'contact_method',
            'documents',
            'contacted_other_party',
            'payment_methods',
            'lead_date',
            'created_at',
            'updated_at',
        ];
        if ($this->customerIds && is_array($this->customerIds) && count($this->customerIds) > 0) {
            return Customer::whereIn('id', $this->customerIds)->get($fields);
        }
        return Customer::all($fields);
    }

    public function headings(): array
    {
        return [
            'id',
            'ac_number',
            'full_name',
            'mobile_number',
            'email',
            'status',
            'assigned_employee_id',
            'sub_department_id',
            'complaint_reason',
            'nationality',
            'city',
            'contact_method',
            'lead_date',
            'created_at',
            'updated_at',
        ];
    }
}
