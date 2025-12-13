<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'expires_at',
        'status'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function files()
    {
        return $this->hasMany(\App\Models\File::class);
    }
};
