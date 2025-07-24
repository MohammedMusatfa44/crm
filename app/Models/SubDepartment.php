<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubDepartment extends Model
{
    use HasFactory;

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
