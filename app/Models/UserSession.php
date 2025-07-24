<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'mac_address',
        'user_agent',
        'login_at',
        'logout_at',
        'is_active',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
