<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Illuminate\Support\Collection;

class CustomerImportDetector implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows, WithEvents
{
    protected $subDepartmentId;
    protected $duplicates = [];
    protected $newCustomers = [];
    protected $allData = [];

    public function __construct($subDepartmentId = null)
    {
        $this->subDepartmentId = $subDepartmentId;
    }

    public function model(array $row)
    {
        // Map Excel columns to database fields with fallbacks
        $acNumber = $row['ac_number'] ?? $row['رقم_الحساب'] ?? $row['رقم الحساب'] ?? null;
        $fullName = $row['full_name'] ?? $row['الاسم_الكامل'] ?? $row['الاسم الكامل'] ?? null;
        $mobileNumber = $row['mobile_number'] ?? $row['رقم_الجوال'] ?? $row['رقم الجوال'] ?? null;
        $email = $row['email'] ?? $row['البريد_الإلكتروني'] ?? $row['البريد الإلكتروني'] ?? null;
        $comment = $row['comment'] ?? $row['التعليق'] ?? null;
        $status = $row['status'] ?? $row['الحالة'] ?? 'new';
        $complaintReason = $row['complaint_reason'] ?? $row['سبب_الشكوى'] ?? $row['سبب الشكوى'] ?? null;
        $nationality = $row['nationality'] ?? $row['الجنسية'] ?? null;
        $city = $row['city'] ?? $row['المدينة'] ?? null;
        $contactMethod = $row['contact_method'] ?? $row['طريقة_التواصل'] ?? $row['طريقة التواصل'] ?? null;
        $contactedOtherParty = $row['contacted_other_party'] ?? $row['تواصل_مع_طرف_آخر'] ?? $row['تواصل مع طرف آخر'] ?? false;
        $paymentMethods = $row['payment_methods'] ?? $row['طرق_الدفع'] ?? $row['طرق الدفع'] ?? null;
        $leadDate = $row['lead_date'] ?? $row['تاريخ_الريادة'] ?? $row['تاريخ الريادة'] ?? null;

        // Check if customer with this ac_number already exists
        $existingCustomer = Customer::where('ac_number', $acNumber)->first();
        
        $customerData = [
            'ac_number' => $acNumber,
            'full_name' => $fullName,
            'mobile_number' => $mobileNumber,
            'email' => $email,
            'comment' => $comment,
            'status' => $status,
            'sub_department_id' => $this->subDepartmentId,
            'assigned_employee_id' => null,
            'complaint_reason' => $complaintReason,
            'nationality' => $nationality,
            'city' => $city,
            'contact_method' => $contactMethod,
            'documents' => null,
            'contacted_other_party' => $contactedOtherParty,
            'payment_methods' => $paymentMethods,
            'lead_date' => $leadDate,
        ];
        
        if ($existingCustomer) {
            // Track duplicate for user decision
            $this->duplicates[] = [
                'ac_number' => $acNumber,
                'existing_customer' => $existingCustomer,
                'new_data' => $customerData,
                'row_data' => $row
            ];
        } else {
            // Track new customer
            $this->newCustomers[] = $customerData;
        }
        
        $this->allData[] = $customerData;
        
        // Don't create any models yet - just collect data
        return null;
    }

    public function rules(): array
    {
        return [
            '*.ac_number' => 'required', // Only ac_number is required
            '*.full_name' => 'nullable|string',
            '*.mobile_number' => 'nullable',
            '*.email' => 'nullable|email',
            '*.status' => ['nullable', Rule::in(['new','in_progress','follow_up','western','hot','closed'])],
            '*.complaint_reason' => 'nullable|string',
            '*.nationality' => 'nullable|string',
            '*.city' => 'nullable|string',
            '*.contact_method' => 'nullable|string',
            '*.contacted_other_party' => 'nullable|boolean',
            '*.payment_methods' => 'nullable|string',
            '*.lead_date' => 'nullable|date',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function(AfterImport $event) {
                // Debug: Log what we're storing
                \Illuminate\Support\Facades\Log::info('CustomerImportDetector - Storing in session:', [
                    'new_customers_count' => count($this->newCustomers),
                    'duplicates_count' => count($this->duplicates),
                    'new_customers_sample' => array_slice($this->newCustomers, 0, 2),
                    'duplicates_sample' => array_slice($this->duplicates, 0, 2),
                ]);
                
                // Store detection results in session for user decision
                session([
                    'import_detection' => [
                        'new_customers' => $this->newCustomers,
                        'duplicates' => $this->duplicates,
                        'total_new' => count($this->newCustomers),
                        'total_duplicates' => count($this->duplicates),
                        'all_data' => $this->allData,
                        'sub_department_id' => $this->subDepartmentId,
                    ]
                ]);
            },
        ];
    }

    public function getDuplicates()
    {
        return $this->duplicates;
    }

    public function getNewCustomers()
    {
        return $this->newCustomers;
    }

    public function getAllData()
    {
        return $this->allData;
    }
} 