<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubDepartment;
use App\Models\Department;

class SubDepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = Department::all();

        foreach ($departments as $department) {
            // Create 2-3 sub-departments for each department
            $subDepartments = $this->getSubDepartmentsForDepartment($department->name);

            foreach ($subDepartments as $subDepartment) {
                SubDepartment::create([
                    'name' => $subDepartment,
                    'department_id' => $department->id,
                    'is_active' => true,
                ]);
            }
        }
    }

    private function getSubDepartmentsForDepartment($departmentName)
    {
        $subDepartmentsMap = [
            'قسم الشكاوى' => ['شكاوى العملاء', 'شكاوى المنتجات', 'شكاوى الخدمات'],
            'قسم المبيعات' => ['مبيعات مباشرة', 'مبيعات عبر الإنترنت', 'مبيعات الشركات'],
            'قسم الدعم الفني' => ['دعم البرامج', 'دعم الأجهزة', 'دعم الشبكات'],
            'قسم التسويق' => ['التسويق الرقمي', 'التسويق التقليدي', 'علاقات عامة'],
            'قسم الموارد البشرية' => ['التوظيف', 'التدريب', 'الرواتب'],
            'قسم المحاسبة' => ['المحاسبة المالية', 'محاسبة التكاليف', 'المراجعة'],
            'قسم الإنتاج' => ['إنتاج السلع', 'مراقبة الجودة', 'الصيانة'],
            'قسم الجودة' => ['ضمان الجودة', 'مراقبة الجودة', 'تحسين الجودة']
        ];

        return $subDepartmentsMap[$departmentName] ?? ['قسم فرعي 1', 'قسم فرعي 2'];
    }
}
