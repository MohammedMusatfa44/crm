<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            'قسم الشكاوى',
            'قسم المبيعات',
            'قسم الدعم الفني',
            'قسم التسويق',
            'قسم الموارد البشرية',
            'قسم المحاسبة',
            'قسم الإنتاج',
            'قسم الجودة'
        ];

        foreach ($departments as $department) {
            Department::create([
                'name' => $department,
                'is_active' => true,
            ]);
        }
    }
}
