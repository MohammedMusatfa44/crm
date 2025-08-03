<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'customer_id',
        'user_id',
        'remind_at',
        'is_read',
        'is_triggered',
    ];

    protected $casts = [
        'remind_at' => 'datetime',
        'is_read' => 'boolean',
        'is_triggered' => 'boolean',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
