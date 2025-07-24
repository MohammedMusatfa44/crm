<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Customer::create([
            'ac_number' => '1001',
            'full_name' => 'أحمد علي',
            'mobile_number' => '0500000001',
            'email' => 'ahmed@example.com',
            'status' => 'new',
            'assigned_employee_id' => 1,
            'sub_department_id' => 1,
            'lead_date' => now(),
        ]);
        \App\Models\Customer::create([
            'ac_number' => '1002',
            'full_name' => 'سارة محمد',
            'mobile_number' => '0500000002',
            'email' => 'sara@example.com',
            'status' => 'in_progress',
            'assigned_employee_id' => 1,
            'sub_department_id' => 2,
            'lead_date' => now(),
        ]);
    }
}
