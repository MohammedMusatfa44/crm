<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Department::create([
            'name' => 'قسم خدمة العملاء',
            'description' => 'قسم يهتم بخدمة العملاء',
            'is_active' => true,
        ]);
        \App\Models\Department::create([
            'name' => 'قسم الدعم الفني',
            'description' => 'قسم يهتم بالدعم الفني',
            'is_active' => true,
        ]);
    }
}
