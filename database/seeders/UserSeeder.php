<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@crm.com',
            'password' => Hash::make('123456'),
            'role' => 'super_admin',
            'is_active' => true,
            'phone' => '0500000000',
        ]);

        // Create Admin users
        $admins = [
            ['name' => 'أحمد محمد', 'email' => 'ahmed@crm.com', 'phone' => '0500000001'],
            ['name' => 'سارة علي', 'email' => 'sara@crm.com', 'phone' => '0500000002'],
            ['name' => 'محمد حسن', 'email' => 'mohamed@crm.com', 'phone' => '0500000003'],
        ];

        foreach ($admins as $admin) {
            User::create([
                'name' => $admin['name'],
                'email' => $admin['email'],
                'password' => Hash::make('123456'),
                'role' => 'admin',
                'is_active' => true,
                'phone' => $admin['phone'],
            ]);
        }

        // Create Employee users
        $employees = [
            ['name' => 'علي أحمد', 'email' => 'ali@crm.com', 'phone' => '0500000004'],
            ['name' => 'فاطمة محمد', 'email' => 'fatima@crm.com', 'phone' => '0500000005'],
            ['name' => 'حسن علي', 'email' => 'hassan@crm.com', 'phone' => '0500000006'],
            ['name' => 'مريم أحمد', 'email' => 'maryam@crm.com', 'phone' => '0500000007'],
            ['name' => 'عبدالله محمد', 'email' => 'abdullah@crm.com', 'phone' => '0500000008'],
            ['name' => 'زينب علي', 'email' => 'zainab@crm.com', 'phone' => '0500000009'],
            ['name' => 'إبراهيم أحمد', 'email' => 'ibrahim@crm.com', 'phone' => '0500000010'],
            ['name' => 'خديجة محمد', 'email' => 'khadija@crm.com', 'phone' => '0500000011'],
            ['name' => 'يوسف علي', 'email' => 'youssef@crm.com', 'phone' => '0500000012'],
            ['name' => 'نورا أحمد', 'email' => 'nora@crm.com', 'phone' => '0500000013'],
            ['name' => 'طارق محمد', 'email' => 'tarek@crm.com', 'phone' => '0500000014'],
            ['name' => 'رنا علي', 'email' => 'rana@crm.com', 'phone' => '0500000015'],
            ['name' => 'كريم أحمد', 'email' => 'kareem@crm.com', 'phone' => '0500000016'],
            ['name' => 'إيمان محمد', 'email' => 'eman@crm.com', 'phone' => '0500000017'],
            ['name' => 'وائل علي', 'email' => 'wael@crm.com', 'phone' => '0500000018'],
        ];

        foreach ($employees as $employee) {
            User::create([
                'name' => $employee['name'],
                'email' => $employee['email'],
                'password' => Hash::make('123456'),
                'role' => 'employee',
                'is_active' => true,
                'phone' => $employee['phone'],
            ]);
        }
    }
}
