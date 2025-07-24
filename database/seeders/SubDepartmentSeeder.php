<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\SubDepartment::create([
            'name' => 'فرعي 1',
            'description' => 'فرعي تابع لقسم خدمة العملاء',
            'department_id' => 1,
            'is_active' => true,
        ]);
        \App\Models\SubDepartment::create([
            'name' => 'فرعي 2',
            'description' => 'فرعي تابع لقسم الدعم الفني',
            'department_id' => 2,
            'is_active' => true,
        ]);
    }
}
