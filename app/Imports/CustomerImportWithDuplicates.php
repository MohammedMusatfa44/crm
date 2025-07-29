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

class CustomerImportWithDuplicates implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows, WithEvents
{
    protected $subDepartmentId;
    protected $duplicates = [];
    protected $created = [];
    protected $updated = [];

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
        
        if ($existingCustomer) {
            // Track duplicate for reporting
            $this->duplicates[] = [
                'ac_number' => $acNumber,
                'existing_name' => $existingCustomer->full_name,
                'new_name' => $fullName,
                'row_data' => $row
            ];
            
            // Update existing customer with new data (only if new data is provided)
            $updateData = [];
            if ($fullName) $updateData['full_name'] = $fullName;
            if ($mobileNumber) $updateData['mobile_number'] = $mobileNumber;
            if ($email) $updateData['email'] = $email;
            if ($comment) $updateData['comment'] = $comment;
            if ($status) $updateData['status'] = $status;
            if ($this->subDepartmentId) $updateData['sub_department_id'] = $this->subDepartmentId;
            if ($complaintReason) $updateData['complaint_reason'] = $complaintReason;
            if ($nationality) $updateData['nationality'] = $nationality;
            if ($city) $updateData['city'] = $city;
            if ($contactMethod) $updateData['contact_method'] = $contactMethod;
            if ($contactedOtherParty !== null) $updateData['contacted_other_party'] = $contactedOtherParty;
            if ($paymentMethods) $updateData['payment_methods'] = $paymentMethods;
            if ($leadDate) $updateData['lead_date'] = $leadDate;
            
            if (!empty($updateData)) {
                $existingCustomer->update($updateData);
                $this->updated[] = $acNumber;
            }
            
            return null; // Don't create new record
        }

        // Create new customer
        $this->created[] = $acNumber;
        
        return new Customer([
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
        ]);
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
                // Store import results in session for display
                session([
                    'import_results' => [
                        'created' => count($this->created),
                        'updated' => count($this->updated),
                        'duplicates' => $this->duplicates,
                        'created_list' => $this->created,
                        'updated_list' => $this->updated,
                    ]
                ]);
            },
        ];
    }

    public function getDuplicates()
    {
        return $this->duplicates;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }
} 