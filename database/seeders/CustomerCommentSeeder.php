<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\User;
use App\Models\CustomerComment;

class CustomerCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();
        $users = User::all();

        if ($customers->count() > 0 && $users->count() > 0) {
            $sampleComments = [
                'تم التواصل مع العميل وتم الاتفاق على الموعد',
                'العميل مهتم بالخدمة وسيتم المتابعة',
                'تم إرسال العرض التجاري للعميل',
                'العميل طلب وقت للتفكير في العرض',
                'تم إجراء المكالمة المتابعة مع العميل',
                'العميل وافق على الشروط وسيتم إتمام الصفقة',
                'تم إرسال العقد للعميل للمراجعة',
                'العميل لديه بعض الاستفسارات حول الخدمة',
                'تم الرد على استفسارات العميل',
                'العميل سعيد بالخدمة المقدمة'
            ];

            foreach ($customers as $customer) {
                // Add 1-3 random comments for each customer
                $commentCount = rand(1, 3);

                for ($i = 0; $i < $commentCount; $i++) {
                    CustomerComment::create([
                        'customer_id' => $customer->id,
                        'user_id' => $users->random()->id,
                        'comment' => $sampleComments[array_rand($sampleComments)]
                    ]);
                }
            }
        }
    }
}
