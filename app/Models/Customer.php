<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Relationships
    public function subDepartment()
    {
        return $this->belongsTo(SubDepartment::class);
    }

    public function assignedEmployee()
    {
        return $this->belongsTo(User::class, 'assigned_employee_id');
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
