<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
    ];

    // Relationships
    public function subDepartments()
    {
        return $this->hasMany(SubDepartment::class);
    }

    public function customers()
    {
        return $this->hasManyThrough(Customer::class, SubDepartment::class);
    }
}
