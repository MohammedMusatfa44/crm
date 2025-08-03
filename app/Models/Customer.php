<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'ac_number',
        'full_name',
        'mobile_number',
        'email',
        'comment',
        'status',
        'assigned_employee_id',
        'sub_department_id',
        'complaint_reason',
        'nationality',
        'city',
        'contact_method',
        'documents',
        'contacted_other_party',
        'payment_methods',
        'lead_date',
        'created_by',
    ];

    protected $casts = [
        'documents' => 'array',
        'payment_methods' => 'array',
        'contacted_other_party' => 'boolean',
        'lead_date' => 'date',
    ];

    // Relationships
    public function subDepartment()
    {
        return $this->belongsTo(SubDepartment::class);
    }

    public function assignedEmployee()
    {
        return $this->belongsTo(User::class, 'assigned_employee_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function notes()
    {
        return $this->hasMany(CustomerNote::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
