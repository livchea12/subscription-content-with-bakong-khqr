<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'expired_at',
        'is_used',
        'jti'
    ];

    protected function casts(): array
    {
        return [
            'expired_at' => 'datetime',
            'token' => 'hashed',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
