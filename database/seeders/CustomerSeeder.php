<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\User;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        // Get all departments, sub-departments, and users for random assignment
        $departments = Department::all();
        $subDepartments = SubDepartment::all();
        $users = User::all();

        // Arabic names for customers
        $arabicNames = [
            'أحمد محمد علي', 'فاطمة حسن أحمد', 'علي عبدالله محمد', 'مريم سعيد حسن',
            'محمد إبراهيم علي', 'خديجة أحمد محمد', 'عبدالله حسن علي', 'زينب محمد أحمد',
            'حسن علي محمد', 'عائشة عبدالله حسن', 'إبراهيم محمد علي', 'نورا أحمد محمد',
            'سعيد حسن علي', 'رنا محمد أحمد', 'عبدالرحمن علي محمد', 'ليلى حسن أحمد',
            'يوسف محمد علي', 'سارة عبدالله حسن', 'عمر أحمد محمد', 'فدوى علي حسن',
            'خالد محمد علي', 'هند أحمد محمد', 'محمود حسن علي', 'نادية محمد أحمد',
            'طارق علي محمد', 'سمية حسن أحمد', 'باسم محمد علي', 'رانيا عبدالله حسن',
            'نادر أحمد محمد', 'دعاء علي حسن', 'كريم محمد علي', 'إيمان أحمد محمد',
            'وائل حسن علي', 'سلمى محمد أحمد', 'رامي علي محمد', 'مروة حسن أحمد',
            'أيمن محمد علي', 'هالة أحمد محمد', 'شادي حسن علي', 'نور محمد أحمد',
            'أشرف علي محمد', 'منى حسن أحمد', 'محمد علي حسن', 'سارة أحمد محمد',
            'علي حسن محمد', 'فاطمة علي أحمد', 'أحمد محمد حسن', 'مريم علي محمد',
            'حسن أحمد علي', 'زينب محمد حسن', 'عبدالله علي محمد', 'خديجة حسن أحمد'
        ];

        // Status options
        $statuses = ['new', 'in_progress', 'follow_up', 'western', 'hot', 'closed'];

        // Cities
        $cities = ['الرياض', 'جدة', 'الدمام', 'مكة', 'المدينة', 'تبوك', 'أبها', 'حائل', 'القصيم', 'الجوف'];

        // Nationalities
        $nationalities = ['سعودي', 'مصري', 'سوري', 'لبناني', 'أردني', 'عراقي', 'كويتي', 'إماراتي', 'قطري', 'عماني'];

        // Contact methods
        $contactMethods = ['هاتف', 'واتساب', 'إيميل', 'زيارة', 'وسائل التواصل الاجتماعي'];

        // Generate 50 customers
        for ($i = 1; $i <= 50; $i++) {
            $randomSubDepartment = $subDepartments->random();
            $randomUser = $users->random();

            Customer::create([
                'ac_number' => 'AC' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'full_name' => $arabicNames[array_rand($arabicNames)],
                'mobile_number' => '05' . rand(10000000, 99999999),
                'email' => 'customer' . $i . '@example.com',
                'comment' => 'تعليق تجريبي للعميل رقم ' . $i,
                'status' => $statuses[array_rand($statuses)],
                'assigned_employee_id' => $randomUser->id,
                'sub_department_id' => $randomSubDepartment->id,
                'complaint_reason' => 'سبب الشكوى للعميل رقم ' . $i,
                'nationality' => $nationalities[array_rand($nationalities)],
                'city' => $cities[array_rand($cities)],
                'contact_method' => $contactMethods[array_rand($contactMethods)],
                'documents' => json_encode(['document1.pdf', 'document2.pdf']),
                'contacted_other_party' => rand(0, 1),
                'payment_methods' => json_encode(['بطاقة ائتمان', 'تحويل بنكي']),
                'lead_date' => now()->subDays(rand(1, 365)),
            ]);
        }
    }
}
